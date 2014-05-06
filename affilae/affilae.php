<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

class Affilae extends Module
{	
  function __construct()
  {
    $this->name = 'affilae';
    $this->tab = 'advertising_marketing';
    $this->version = '1.5';
    $this->author = 'affilae.com';
    $this->displayName = 'affilae';
    

    parent::__construct();

    if ($this->id AND !Configuration::get('AFFILAE_RULES')) $this->warning = "Vous n'avez pas encore spécifié de règle de commissionnement.";

    if ($this->id AND !Configuration::get('AFFILAE_SCRIPT')) $this->warning = "Vous n'avez pas encore ajouté votre script de sécurité.";

    $this->description = "Intègre le tracking affilae sur votre page de confirmation de commande.";
    $this->confirmUninstall = "Etes-vous sûr de vouloir supprimer vos paramètres ?";
  }

  function install()
  {
    return (parent::install() AND $this->registerHook('footer') AND $this->registerHook('orderConfirmation'));
  }

  function uninstall()
  {
    if (!Configuration::deleteByName('AFFILAE_RULES') OR !parent::uninstall()) return false;
    if (!Configuration::deleteByName('AFFILAE_SCRIPT') OR !parent::uninstall()) return false;
    return true;
  }

  public function getContent()
  {
    global $smarty, $currentIndex, $cookie;
    
    $script = Configuration::get('AFFILAE_SCRIPT');
    
    $rulesDb = Configuration::get('AFFILAE_RULES');
    if($rulesDb) $rules = unserialize(stripslashes($rulesDb));
    else $rules = array();
    
    $confirmMessage = false;
    $indexedCategories = array();
    
    switch (Tools::getValue('action'))
    {
      case 'delete':
        unset($rules[Tools::getValue('rule')]);
        if(count($rules) == 0) {
          Configuration::updateValue('AFFILAE_RULES', null);
          $rules = null;
        }
        else Configuration::updateValue('AFFILAE_RULES', serialize($rules));
        Tools::redirectAdmin($currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&successDelete=true');
      case 'add':
        if (Tools::isSubmit('submitAffilae')) $this->processForm($smarty, $currentIndex, $rules, $indexedCategories);
        
        $categories = Category::getCategories((int)($cookie->id_lang), false);
        $cats = $this->recurseCategoryForInclude(null, $indexedCategories, $categories, $categories[0][1], 1);
        
        $smarty->assign('cats', $cats);
        $smarty->assign('path', $this->_path);
        $smarty->assign('cancelLink', $currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
        return $this->display(__FILE__, 'form.php');

      break;
      case 'edit':
        if (Tools::isSubmit('submitAffilae')) $this->processForm($smarty, $currentIndex, $rules, $indexedCategories);
        
        $rule = $rules[Tools::getValue('rule')];
        
        $smarty->assign('affilaeTitle', $rule['title']);
        $smarty->assign('affilaeCode', $rule['code']);
        $smarty->assign('affilaeHasCategories', $rule['has_cat']);
        
        if($rule['has_cat'] == 'choose') $indexedCategories = $rule['categories'];
        
        $categories = Category::getCategories((int)($cookie->id_lang), false);
        $cats = $this->recurseCategoryForInclude(null, $indexedCategories, $categories, $categories[0][1], 1);
        
        $smarty->assign('cats', $cats);
        $smarty->assign('path', $this->_path);
        $smarty->assign('cancelLink', $currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
        return $this->display(__FILE__, 'form.php');
      break;
      default: //LIST RULES
        if(count($rules) > 0)
        {
          foreach($rules as $key => $rule)
          {
            if($rule['has_cat'] == 'choose')
            {
              unset($rules[$key]['categories']);
              $catObjects = Category::getCategoryInformations($rule['categories'], (int)($cookie->id_lang));

              foreach($catObjects as $ck => $currentCat)
              {
                $rules[$key]['categories'][] = $currentCat;
              }
            }
          }
        }
        
        if (Tools::isSubmit('submitScriptAffilae'))
        {
          $submitValue = Tools::getValue('affilae_script', null);
          if(!empty($submitValue))
          {
            Configuration::updateValue('AFFILAE_SCRIPT', htmlentities(Tools::getValue('affilae_script')));
            Tools::redirectAdmin($currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&successScript=true');
          }
          else
          {
            Tools::redirectAdmin($currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
          }
        }
        
        $smarty->assign('script', $script);
        $smarty->assign('successScript', Tools::getValue('successScript', false));
        $smarty->assign('successAdd', Tools::getValue('successAdd', false));
        $smarty->assign('successEdit', Tools::getValue('successEdit', false));
        $smarty->assign('successDelete', Tools::getValue('successDelete', false));
        $smarty->assign('rules', $rules);
        $smarty->assign('path', $this->_path);
        $smarty->assign('addLink', $currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&action=add');
        $smarty->assign('editLink', $currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&action=edit');
        $smarty->assign('deleteLink', $currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&action=delete');
        return $this->display(__FILE__, 'list.php');
      break;
    }
    
  }
  
  private function processForm($smarty, $currentIndex, $rules, $indexedCategories)
  {
    $hasError = false;
    $rule_title = Tools::getValue('affilae_title');
    $rule_code = Tools::getValue('affilae_code');
    $rule_has_categories = Tools::getValue('affilae_has_categories');
    $rule_categories = Tools::getValue('affilae_categoryBox');

    $smarty->assign('affilaeTitle', $rule_title);
    $smarty->assign('affilaeCode', $rule_code);
    $smarty->assign('affilaeHasCategories', $rule_has_categories);

    if($rule_has_categories == 'choose')
    {
      foreach ($rule_categories AS $k => $row) $indexedCategories[] = $row;
    }
    

    if(empty($rule_title))
    {
      $hasError = true;
      $titleError = "Veuillez saisir un titre.";
      $smarty->assign('titleError', $titleError);
    }
    if(empty($rule_code))
    {
      $hasError = true;
      $codeError = "Veuillez saisir votre code de Tracking.";
      $smarty->assign('codeError', $codeError);
    }
    if(!preg_match('#^[0-9a-zA-Z]+\-[0-9a-zA-Z]+$#', $rule_code))
    {
      $hasError = true;
      $codeError = "Le code de tracking n'est pas valide.";
      $smarty->assign('codeError', $codeError);
    }
    if($rule_has_categories != 'all' AND $rule_has_categories != 'choose')
    {
      $hasError = true;
      $catError = "Le choix des catégories concernées n'est pas correct.";
      $smarty->assign('catError', $catError);
    }
    if($rule_has_categories == 'all' AND count($rule_categories) == 0)
    {
      $hasError = true;
      $catError = "Veuillez choisir au moins une catégorie.";
      $smarty->assign('catError', $catError);
    }

    if(!$hasError)
    {
      $id = (int) Tools::getValue('rule');
      if(Tools::getValue('action') == 'edit')
      {
        $rules[$id]['title'] = trim($rule_title);
        $rules[$id]['code'] = $rule_code;
        $rules[$id]['has_cat'] = $rule_has_categories;
        $rules[$id]['categories'] = $rule_categories;

        Configuration::updateValue('AFFILAE_RULES', serialize($rules));
        Tools::redirectAdmin($currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&successEdit=true');
      }
      else
      {
        $newRule = array();
        $newRule['title'] = trim($rule_title);
        $newRule['code'] = $rule_code;
        $newRule['has_cat'] = $rule_has_categories;
        $newRule['categories'] = $rule_categories;

        $rules[] = $newRule;
        
        Configuration::updateValue('AFFILAE_RULES', serialize($rules));
        Tools::redirectAdmin($currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&successAdd=true');
      }
    }
  }
  
   /**
   * Build a categories tree
   *
   * @param array $indexedCategories Array with categories where product is indexed (in order to check checkbox)
   * @param array $categories Categories to list
   * @param array $current Current category
   * @param integer $id_category Current category id
   */
  public static function recurseCategoryForInclude($id_obj, $indexedCategories, $categories, $current, $id_category = 1, $id_category_default = NULL, $has_suite = array())
  {
      global $done;
      static $irow;

      if (!isset($done[$current['infos']['id_parent']]))
          $done[$current['infos']['id_parent']] = 0;
      $done[$current['infos']['id_parent']] += 1;

      $todo = sizeof($categories[$current['infos']['id_parent']]);
      $doneC = $done[$current['infos']['id_parent']];

      $level = $current['infos']['level_depth'] + 1;

      $return = '
      <tr class="'.($irow++ % 2 ? 'alt_row' : '').'">
          <td>
              <input type="checkbox" name="affilae_categoryBox[]" class="categoryBox'.($id_category_default == $id_category ? ' id_category_default' : '').'" id="categoryBox_'.$id_category.'" value="'.$id_category.'"'.((in_array($id_category, $indexedCategories) OR ((int)(Tools::getValue('id_category')) == $id_category AND !(int)($id_obj))) ? ' checked="checked"' : '').' />
          </td>
          <td>
              '.$id_category.'
          </td>
          <td>';
          for ($i = 2; $i < $level; $i++)
              $return .= '<img src="../img/admin/lvl_'.$has_suite[$i - 2].'.gif" alt="" style="vertical-align: middle;"/>';
          $return .= '<img src="../img/admin/'.($level == 1 ? 'lv1.gif' : 'lv2_'.($todo == $doneC ? 'f' : 'b').'.gif').'" alt="" style="vertical-align: middle;"/> &nbsp;
          <label for="categoryBox_'.$id_category.'" class="t">'.stripslashes($current['infos']['name']).'</label></td>
      </tr>';

      if ($level > 1) $has_suite[] = ($todo == $doneC ? 0 : 1);
      
      if (isset($categories[$id_category]))
      {
        foreach ($categories[$id_category] AS $key => $row)
        {
            if ($key != 'infos') $return .= self::recurseCategoryForInclude($id_obj, $indexedCategories, $categories, $categories[$id_category][$key], $key, $id_category_default, $has_suite);
        }
      }
      return $return;
  }
  
  public function hookFooter($params)
  {
    $script = Configuration::get('AFFILAE_SCRIPT');
    if(!empty($script)) return html_entity_decode ($script);
  }
  
  function getPaymentName($moduleName)
  {
    if($moduleName == 'bankwire') return 'bankwire';
    if($moduleName == 'cheque') return 'cheque';
    
    $method = 'other';
    
    $moduleName = strtolower($moduleName);
    if(strpos($moduleName, 'paypal') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'atos') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'allopass') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'rentabiliweb') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'hipay') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'clickandbuy') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'sofinco') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'securepay') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'paypoint') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'cmcic') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'kwixo') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'receiveandpay ') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'systempay ') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'vads ') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'sips ') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'paybox ') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'cetrel ') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'payline ') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'spplus ') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'buyster ') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'paygate ') !== FALSE) $method = 'online';
    if(strpos($moduleName, 'starpass ') !== FALSE) $method = 'online';

    return $method;
  }
  function hookOrderConfirmation($params)
  {
    global $smarty;
    
    $rules = unserialize(Configuration::get('AFFILAE_RULES'));
    
    if(count($rules) == 0) return '';

    $order = $params['objOrder'];
    $orderId = $order->id;
    $customerId = $order->id_customer;
    $total = $order->total_products; //without taxes
    $payment = self::getPaymentName($order->module);
    $trackings = array();

    if (Validate::isLoadedObject($order))
    {
      $hasCategories = false;
      $rulesWithCategories = array();
      $otherRules = array();
      
      foreach($rules as $rule) //parse rules for categories
      {
        if($rule['has_cat'] == 'choose')
        {
          $hasCategories = true;
          $rulesWithCategories[] = $rule;
        }
        else $otherRules[] = $rule;
      }

      if($hasCategories)
      {
        $products = $order->getProducts(); //return array Products with price, quantity (with taxe and without)
        
        $categories = array();
        $totalForCategories = 0;
        
        //retrieve default category id and sort products by categories
        foreach($products as $product)
        {
          $categoryId = Db::getInstance()->getValue('
              SELECT id_category_default FROM `'._DB_PREFIX_.'product` p
              WHERE p.id_product = '.(int)($product['product_id']));
          $categories[$categoryId][] = $product;
        }
       
        //create tracking codes for each rules
        foreach($rulesWithCategories as $ruleWithCategories)
        {
          $productsForThisRule = array();
          //get products for each categories of this rule
          foreach($ruleWithCategories['categories'] as $category)
          {
            if(isset($categories[$category])) $productsForThisRule = array_merge($productsForThisRule, $categories[$category]);
          }
          $totalForThisRule = 0;
          //count the total paid with taxes for this rule
          foreach($productsForThisRule as $p)
          {
            $totalForThisRule += $p['total_wt'];
            $totalForCategories += $p['total_wt'];
          }
          if($totalForThisRule > 0) $trackings[] = array('code'=>$ruleWithCategories['code'], 'total'=>$totalForThisRule, 'id'=>$orderId, 'customerId'=>$customerId, 'payment'=>$payment);
        }
        
        //others rules for rest of products, uses the first remaining rule
        if(count($otherRules) > 0)
        {
          $totalRest = $total-$totalForCategories;
          if($totalRest > 0) $trackings[] = array('code'=>$otherRules[0]['code'], 'total'=>$totalRest, 'id'=>$orderId, 'customerId'=>$customerId, 'payment'=>$payment);
        }
      }
      elseif(count($otherRules) > 0)
      {
        foreach($otherRules as $rule) $trackings[] = array('code'=>$rule['code'], 'total'=>$total, 'id'=>$orderId, 'customerId'=>$customerId, 'payment'=>$payment);
      }
      
      $smarty->assign('trackings', $trackings);
      return $this->display(__FILE__, 'tracking.tpl');
    }

  }
}

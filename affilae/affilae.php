<?php
/**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 *
 *  @author    Affilae <contact@affilae.com>
 *  @copyright 2007-2016 Affilae
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of Affilae
 */

class Affilae extends Module
{
    public function __construct()
    {
        $this->name        = 'affilae';
        $this->tab         = 'advertising_marketing';
        $this->version     = '1.6.0';
        $this->author      = 'affilae.com';
        $this->displayName = $this->l('affilae');
        $this->bootstrap   = true;

        // Product Key required to tie to our account
        $this->module_key = '1ef12062b1f4433e5df2cf5972e39542';

        parent::__construct();

        if ($this->id && !Configuration::get('AFFILAE_RULES')) {
            $this->warning = $this->l('You have not specified any commission rule.');
        }

        if ($this->id && !Configuration::get('AFFILAE_SCRIPT')) {
            $this->warning = $this->l('You have not added your security script.');
        }

        $this->description = $this->l('Integrates Affilae tracking on your order confirmation page.');
        $this->confirmUninstall = $this->l('Are you sure you want to delete your settings?');
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('footer')
            && $this->registerHook('orderConfirmation');
    }

    public function uninstall()
    {
        return Configuration::deleteByName('AFFILAE_RULES')
            && Configuration::deleteByName('AFFILAE_SCRIPT')
            && parent::uninstall();
    }

    public function getContent()
    {
        $currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab;

        $script  = Configuration::get('AFFILAE_SCRIPT');
        $rulesDb = Configuration::get('AFFILAE_RULES');

        if ($rulesDb) {
            $rules = unserialize(Tools::stripslashes($rulesDb));
        } else {
            $rules = array();
        }

        $indexedCategories = array();

        $content = '';
        switch (Tools::getValue('action')) {
            case 'remove':
                unset($rules[Tools::getValue('rule')]);

                if (count($rules) == 0) {
                    Configuration::updateValue('AFFILAE_RULES', null);
                    $rules = null;
                } else {
                    Configuration::updateValue('AFFILAE_RULES', serialize($rules));
                }

                Tools::redirectAdmin(
                    $currentIndex
                    . '&configure=' . $this->name
                    . '&token=' . Tools::getAdminTokenLite('AdminModules')
                    . '&successDelete=true'
                );
                break;
            case 'add':
                if (Tools::isSubmit('submitAffilae')) {
                    $this->processForm($currentIndex, $rules, $indexedCategories);
                }

                $root = Category::getRootCategory();

                $tree = new HelperTreeCategories('associated-categories-tree');
                $tree
                    ->setRootCategory((int)$root->id)
                    ->setUseCheckBox(true)
                    ->setSelectedCategories([]);

                $this->smarty->assign('category_tree', $tree->render());
                $this->smarty->assign('path', $this->_path);
                $this->smarty->assign(
                    'cancelLink',
                    $currentIndex
                    . '&configure=' . $this->name
                    . '&token=' . Tools::getAdminTokenLite('AdminModules')
                );

                $content = $this->display(__FILE__, 'views/templates/admin/form.tpl');
                break;
            case 'edit':
                if (Tools::isSubmit('submitAffilae')) {
                    $this->processForm($currentIndex, $rules, $indexedCategories);
                }

                $rule = $rules[Tools::getValue('rule')];

                $this->smarty->assign('affilaeTitle', $rule['title']);
                $this->smarty->assign('affilaeCode', $rule['code']);
                $this->smarty->assign('affilaeHasCategories', $rule['has_cat']);

                if ($rule['has_cat'] == 'choose') {
                    $indexedCategories = $rule['categories'];
                }

                $root = Category::getRootCategory();

                $tree = new HelperTreeCategories('associated-categories-tree');
                $tree
                    ->setRootCategory((int)$root->id)
                    ->setUseCheckBox(true)
                    ->setSelectedCategories($indexedCategories);

                $this->smarty->assign('category_tree', $tree->render());
                $this->smarty->assign('path', $this->_path);
                $this->smarty->assign(
                    'cancelLink',
                    $currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules')
                );

                $content = $this->display(__FILE__, 'views/templates/admin/form.tpl');
                break;
            default: //LIST RULES
                if (count($rules) > 0) {
                    foreach ($rules as $key => $rule) {
                        if ($rule['has_cat'] == 'choose') {
                            unset($rules[$key]['categories']);
                            $catObjects = Category::getCategoryInformations(
                                $rule['categories'],
                                (int) ($this->context->cookie->id_lang)
                            );

                            foreach ($catObjects as $currentCat) {
                                $rules[$key]['categories'][] = $currentCat;
                            }
                        }
                    }
                }

                if (Tools::isSubmit('submitScriptAffilae')) {
                    $submitValue = Tools::getValue('affilae_script', null);
                    if (!empty($submitValue)) {
                        Configuration::updateValue('AFFILAE_SCRIPT', htmlentities(Tools::getValue('affilae_script')));
                        Tools::redirectAdmin($currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&successScript=true');
                    } else {
                        Tools::redirectAdmin($currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
                    }
                }

                $this->smarty->assign('script', html_entity_decode($script));
                $this->smarty->assign('successScript', Tools::getValue('successScript', false));
                $this->smarty->assign('successAdd', Tools::getValue('successAdd', false));
                $this->smarty->assign('successEdit', Tools::getValue('successEdit', false));
                $this->smarty->assign('successDelete', Tools::getValue('successDelete', false));
                $this->smarty->assign('rules', $rules);
                $this->smarty->assign('path', $this->_path);
                $this->smarty->assign('addLink', $currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&action=add');
                $this->smarty->assign('editLink', $currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&action=edit');
                $this->smarty->assign('deleteLink', $currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&action=remove');

                $content = $this->display(__FILE__, 'views/templates/admin/list.tpl');
                break;
        }

        return $content;
    }

    private function processForm($currentIndex, $rules, $indexedCategories)
    {
        $hasError = false;
        $rule_title = Tools::getValue('affilae_title');
        $rule_code = Tools::getValue('affilae_code');
        $rule_has_categories = Tools::getValue('affilae_has_categories');
        $rule_categories = Tools::getValue('categoryBox');

        $this->smarty->assign('affilaeTitle', $rule_title);
        $this->smarty->assign('affilaeCode', $rule_code);
        $this->smarty->assign('affilaeHasCategories', $rule_has_categories);

        if (empty($rule_title)) {
            $hasError = true;
            $titleError = $this->l('Please enter a title.');
            $this->smarty->assign('titleError', $titleError);
        }

        if (empty($rule_code)) {
            $hasError = true;
            $codeError = $this->l('Please enter your tracking code.');
            $this->smarty->assign('codeError', $codeError);
        }

        if (!preg_match('#^[0-9a-zA-Z]+\-[0-9a-zA-Z]+$#', $rule_code)) {
            $hasError = true;
            $codeError = $this->l('The tracking code is not valid.');
            $this->smarty->assign('codeError', $codeError);
        }

        if ($rule_has_categories != 'all' && $rule_has_categories != 'choose') {
            $hasError = true;
            $catError = $this->l('The choice of concerned categories is not correct.');
            $this->smarty->assign('catError', $catError);
        }

        if ($rule_has_categories == 'choose' && (!$rule_categories || count($rule_categories) == 0)) {
            $hasError = true;
            $catError = $this->l('Please choose at least one category.');
            $this->smarty->assign('catError', $catError);
        }

        if ($rule_has_categories == 'choose') {
            foreach ($rule_categories as $row) {
                $indexedCategories[] = $row;
            }
        }

        if ($rule_has_categories == 'all') {
            $rule_categories = false;
        }

        if (!$hasError) {
            $id = (int) Tools::getValue('rule');

            if (Tools::getValue('action') == 'edit') {
                $rules[$id]['title'] = trim($rule_title);
                $rules[$id]['code'] = $rule_code;
                $rules[$id]['has_cat'] = $rule_has_categories;
                $rules[$id]['categories'] = $rule_categories;

                Configuration::updateValue('AFFILAE_RULES', serialize($rules));
                Tools::redirectAdmin($currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&successEdit=true');
            } else {
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

    public function hookFooter($params)
    {
        $script = Configuration::get('AFFILAE_SCRIPT');

        if (!empty($script)) {
            return html_entity_decode($script);
        }
    }

    public function getPaymentName($moduleName)
    {
        if ($moduleName == 'bankwire') {
            return 'bankwire';
        }

        if ($moduleName == 'cheque') {
            return 'cheque';
        }

        $method = 'other';

        $moduleName = Tools::strtolower($moduleName);
        if (strpos($moduleName, 'paypal') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'atos') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'allopass') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'rentabiliweb') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'hipay') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'clickandbuy') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'sofinco') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'securepay') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'paypoint') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'cmcic') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'kwixo') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'receiveandpay ') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'systempay ') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'vads ') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'sips ') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'paybox ') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'cetrel ') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'payline ') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'spplus ') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'buyster ') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'paygate ') !== false) {
            $method = 'online';
        }
        if (strpos($moduleName, 'starpass ') !== false) {
            $method = 'online';
        }

        return $method;
    }

    public function hookOrderConfirmation($params)
    {
        $rules = unserialize(Configuration::get('AFFILAE_RULES'));

        if (count($rules) == 0) {
            return '';
        }

        $order = $params['objOrder'];
        $orderId = $order->id;
        $customerId = $order->id_customer;

        $discount = (float) $order->total_discounts_tax_excl;
        $totalProducts = (float) $order->total_products;

        // total is has no rule by products category
        $total = $totalProducts - $discount; //without taxes
        $payment = self::getPaymentName($order->module);
        $trackings = array();

        if (Validate::isLoadedObject($order)) {
            $hasCategories = false;
            $rulesWithCategories = array();
            $otherRules = array();

            foreach ($rules as $rule) { //parse rules for categories
                if ($rule['has_cat'] == 'choose') {
                    $hasCategories = true;
                    $rulesWithCategories[] = $rule;
                } else {
                    $otherRules[] = $rule;
                }
            }

            if ($hasCategories) {
                // if has discount, apply its ratio on all rules total
                if ($discount > 0) {
                    $discountCoef = (($discount * 100) / $totalProducts) / 100;
                } // 50% discount becomes 0.5

                $products = $order->getProducts(); //return array Products with price, quantity (with taxe and without)

                $categories = array();
                $totalForCategories = 0;

                //retrieve default category id and sort products by categories
                foreach ($products as $product) {
                    $categoryId = Db::getInstance()->getValue('
                        SELECT id_category_default FROM `' ._DB_PREFIX_.'product` p
                        WHERE p.id_product = ' .(int) ($product['product_id']));
                    $categories[$categoryId][] = $product;
                }

                //create tracking codes for each rules
                foreach ($rulesWithCategories as $ruleWithCategories) {
                    $productsForThisRule = array();
                    //get products for each categories of this rule
                    foreach ($ruleWithCategories['categories'] as $category) {
                        if (isset($categories[$category])) {
                            $productsForThisRule = array_merge($productsForThisRule, $categories[$category]);
                        }
                    }
                    $totalForThisRule = 0;
                    //count the total paid with taxes for this rule
                    foreach ($productsForThisRule as $p) {
                        $totalForThisRule += $p['total_price']; //without taxes
                        $totalForCategories += $p['total_price']; //without taxes
                    }

                    if ($totalForThisRule > 0) {
                        $discountForThisRule = ($discount > 0) ? $totalForThisRule * $discountCoef : 0;

                        $totalForThisRule = $totalForThisRule - $discountForThisRule;

                        $trackings[] = array(
                            'code' => $ruleWithCategories['code'],
                            'total' => $totalForThisRule,
                            'id' => $orderId,
                            'customerId' => $customerId,
                            'payment' => $payment,
                        );
                    }
                }

                //others rules for rest of products, uses the first remaining rule
                if (count($otherRules) > 0) {
                    $totalRest = $total - $totalForCategories;

                    $discountForThisRule = ($discount > 0) ? $totalRest * $discountCoef : 0;

                    $totalRest = $totalRest - $discountForThisRule;

                    if ($totalRest > 0) {
                        $trackings[] = array(
                            'code' => $otherRules[0]['code'],
                            'total' => $totalRest,
                            'id' => $orderId,
                            'customerId' => $customerId,
                            'payment' => $payment,
                        );
                    }
                }
            } elseif (count($otherRules) > 0) {
                foreach ($otherRules as $rule) {
                    $trackings[] = array(
                        'code' => $rule['code'],
                        'total' => $total,
                        'id' => $orderId,
                        'customerId' => $customerId,
                        'payment' => $payment,
                    );
                }
            }

            $this->smarty->assign('trackings', $trackings);

            return $this->display(__FILE__, 'tracking.tpl');
        }
    }
}

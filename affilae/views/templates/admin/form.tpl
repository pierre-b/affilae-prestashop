{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<p>
    <img src="{$path|escape:'htmlall':'UTF-8'}logo.png" alt="affilae, l'Affiliation pour tous. Cl&eacute; en main." />
</p>

<form action="" method="post" class="defaultForm form-horizontal">
    <div class="panel">
        <div class="panel-heading">
            <i class="icon-cogs"></i>
            {l s='Commission rule' mod='affilae'}
        </div>

        <div class="form-wrapper">
            {if isset($titleError)}
                <div class="conf error"><img src="../img/admin/error.png" alt="" title="" />{$titleError|escape:'htmlall':'UTF-8'}</div>
            {/if}

            {if isset($codeError)}
                <div class="conf error"><img src="../img/admin/error.png" alt="" title="" />{$codeError|escape:'htmlall':'UTF-8'}</div>
            {/if}

            {if isset($catError)}
                <div class="conf error"><img src="../img/admin/error.png" alt="" title="" />{$catError|escape:'htmlall':'UTF-8'}</div>
            {/if}

            <div class="form-group">
                <label class="control-label col-lg-3" for="affilae_title">{l s='Title of the rule' mod='affilae'}</label>

                <div class="col-lg-9">
                    <input type="text" class="form-control" id="affilae_title" name="affilae_title" {if isset($affilaeTitle)}value="{$affilaeTitle|escape:'htmlall':'UTF-8'}"{/if} />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3" for="affilae_code">{l s='Key' mod='affilae'}</label>

                <div class="col-lg-9">
                    <input type="text" id="affilae_code" name="affilae_code" {if isset($affilaeCode)}value="{$affilaeCode|escape:'htmlall':'UTF-8'}"{/if} /> {l s='Format: xx-xx' mod='affilae'}
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3" for="affilae_has_categories">{l s='Categories' mod='affilae'}</label>

                <div class="col-lg-9">
                    <select id="affilae_has_categories" name="affilae_has_categories" onChange="checkType();" style="width: 100px">
                        <option value="all" {if isset($affilaeHasCategories) && $affilaeHasCategories == 'all'}selected="selected"{/if}>{l s='All' mod='affilae'}</option>
                        <option value="choose" {if isset($affilaeHasCategories) && $affilaeHasCategories == 'choose'}selected="selected"{/if}>{l s='Selection' mod='affilae'}</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-9 col-lg-push-3">
                    <div id="cat-table" style="display: none">
                        {$category_tree}
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-footer">
            <button type="submit" name="submitAffilae" id="submitAffilae" class="btn btn-default pull-right">
                <i class="process-icon-save"></i>  {l s='Save' mod='affilae'}
            </button>

            <a class="btn btn-default" href="{$cancelLink|escape:'htmlall':'UTF-8'}" class="btn">{l s='Cancel' mod='affilae'}</a>
        </div>
    </div>
</form>

<script type="text/javascript">
    function checkType() {
        if ($('#affilae_has_categories').val() === 'all') {
            $('#cat-table').hide();
        } else {
            $('#cat-table').show();
        }
    }

    $(document).ready(function () {
        checkType();
    });
</script>
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

{if $successScript}
<div class="alert alert-success">
    {l s='Your security script has been updated.' mod='affilae'}
</div>
{/if}

{if $successAdd}
<div class="alert alert-success">
    {l s='Your new commission rule has been added' mod='affilae'}
</div>
{/if}

{if $successEdit}
<div class="alert alert-success">
    {l s='Your commission rule has been updated' mod='affilae'}
</div>
{/if}

{if $successDelete}
<div class="alert alert-success">
    {l s='Your commission rule has been deleted' mod='affilae'}
</div>
{/if}

<div class="panel">
    <img src="{$path|escape:'htmlall':'UTF-8'}logo.png" alt="affilae, l'Affiliation pour tous" />
    <p>{l s='Thank you for choosing Affilae to manage your affiliate program.' mod='affilae'}</p>
    <p>{l s='The amount of orders transmitted during the conversion is without VAT and without shipping costs.' mod='affilae'}</p>
    <p>{l s='The conversion tracking script is triggered on the order confirmation page.' mod='affilae'}</p>
    <p>{l s='When an order contains several products subject to different rules of commissions, it generates a conversion to chacunes of them in the amount of products affected.' mod='affilae'}</p>
</div>

<form action="" method="post" class="defaultForm form-horizontal" id="affilae-script">
    <div class="panel">
        <div class="panel-heading">
            <i class="icon-cogs"></i> {l s='Security script' mod='affilae'}
        </div>

        <div class="form-wrapper">
            <div class="form-group">
                <label class="control-label col-lg-3" for="script-input">{l s='Security script' mod='affilae'}</label>
                <div class="col-lg-9">
                    <textarea rows="10" name="affilae_script" id="affilae_script">{if isset($script)}{$script|escape:'htmlall':'UTF-8'}{/if}</textarea>
                </div>
            </div>
        </div>

        <div class="panel-footer">
            <button type="submit" name="submitScriptAffilae" id="submitScriptAffilae" class="btn btn-default pull-right">
                <i class="process-icon-save"></i> {l s='Save' mod='affilae'}
            </button>
        </div>
    </form>
</div>

{capture name="delete_confirmation" assign="delete_confirmation"}{l s='Are you sure that you want to delete this commission rule?' mod='affilae'}{/capture}

<div class="panel">
    <div class="panel-heading">
        <i class="icon-cogs"></i> {l s='Commission rules' mod='affilae'}
    </div>

    {if $rules}
        <table class="table">
            <thead>
                <tr>
                    <th>{l s='Rule' mod='affilae'}</th>
                    <th>{l s='Tracking code' mod='affilae'}</th>
                    <th>{l s='Categories' mod='affilae'}</th>
                    <th>{l s='Actions' mod='affilae'}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$rules key=key item=rule}
                <tr>
                    <td>{$rule.title|escape:'htmlall':'UTF-8'}</td>
                    <td>{$rule.code|escape:'htmlall':'UTF-8'}</td>
                    <td>
                        {if $rule.has_cat == 'all'}
                            {l s='All' mod='affilae'}
                        {else}
                            <ul>
                                {foreach from=$rule.categories item=cat}
                                    <li>{$cat.name|escape:'htmlall':'UTF-8'}</li>
                                {/foreach}
                            </ul>
                        {/if}
                    </td>
                    <td>
                        <a href="{$editLink|escape:'htmlall':'UTF-8'}&rule={$key|escape:'htmlall':'UTF-8'}" class="btn btn-default">
                            {l s='Edit' mod='affilae'}
                        </a>
                        <a href="{$deleteLink|escape:'htmlall':'UTF-8'}&rule={$key|escape:'htmlall':'UTF-8'}" class="btn btn-default" onclick="return confirm(\'{$delete_confirmation|escape:'javascript'}\);">
                            {l s='Delete' mod='affilae'}
                        </a>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    {/if}

    <div class="panel-footer">
        <a href="{$addLink|escape:'htmlall':'UTF-8'}" value="Ajouter une r&egrave;gle" class="btn btn-default pull-right">
            <i class="process-icon-new"></i>
            {l s='Add a new commission rules' mod='affilae'}
        </a>
    </div>
</div>

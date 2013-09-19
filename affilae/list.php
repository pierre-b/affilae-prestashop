<style type="text/css">
.btn {
  cursor: pointer;
  display: inline-block;
  background-color: #e6e6e6;
  background-repeat: no-repeat;
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), color-stop(25%, #ffffff), to(#e6e6e6));
  background-image: -webkit-linear-gradient(#ffffff, #ffffff 25%, #e6e6e6);
  background-image: -moz-linear-gradient(top, #ffffff, #ffffff 25%, #e6e6e6);
  background-image: -ms-linear-gradient(#ffffff, #ffffff 25%, #e6e6e6);
  background-image: -o-linear-gradient(#ffffff, #ffffff 25%, #e6e6e6);
  background-image: linear-gradient(#ffffff, #ffffff 25%, #e6e6e6);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#e6e6e6', GradientType=0);
  padding: 5px 14px 6px;
  text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
  color: #333;
  font-size: 15px;
  line-height: normal;
  border: 1px solid #ccc;
  border-bottom-color: #bbb;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
  -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
  -webkit-transition: 0.1s linear all;
  -moz-transition: 0.1s linear all;
  -ms-transition: 0.1s linear all;
  -o-transition: 0.1s linear all;
  transition: 0.1s linear all;
}
.btn:hover {
  background-position: 0 -15px;
  color: #333;
  text-decoration: none;
}
.btn:focus {
  outline: 1px dotted #666;
}
.btn.primary {
  color: #ffffff;
  background-color: #ef3a01;
  background-repeat: repeat-x;
  background-image: -khtml-gradient(linear, left top, left bottom, from(#f99108), to(#ef3a01));
  background-image: -moz-linear-gradient(top, #f99108, #ef3a01);
  background-image: -ms-linear-gradient(top, #f99108, #ef3a01);
  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #f99108), color-stop(100%, #ef3a01));
  background-image: -webkit-linear-gradient(top, #f99108, #ef3a01);
  background-image: -o-linear-gradient(top, #f99108, #ef3a01);
  background-image: linear-gradient(top, #f99108, #ef3a01);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f99108', endColorstr='#ef3a01', GradientType=0);
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
  border-color: #ef3a01 #ef3a01 #a32801;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
}
a.affilae, a.affilae:visited  { color: #f17100; }
a.affilae:hover { text-decoration: underline; }
table.affilae {
  width: 100%;
  margin-bottom: 20px;
  padding: 0;
  font-size: 15px;
  border-collapse: collapse;
}
table.affilae th, table.affilae td {
  padding: 10px 10px 9px;
  line-height: 20px;
  text-align: left;
}
table.affilae th {
  padding-top: 9px;
  font-weight: bold;
  vertical-align: middle;
}
table.affilae td {
  vertical-align: top;
  border-top: 1px solid #ddd;
}
table.affilae tbody th {
  border-top: 1px solid #ddd;
  vertical-align: top;
}
.zebra-striped tbody tr:nth-child(odd) td, .zebra-striped tbody tr:nth-child(odd) th {
  background-color: #f9f9f9;
}
.zebra-striped tbody tr:hover td, .zebra-striped tbody tr:hover th {
  background-color: #f5f5f5;
}
</style>

<center>
    <img src="{$path}logo.png" alt="affilae, l'Affiliation pour tous" />
    <p><b>Merci d'avoir choisi <a href="https://affilae.com" title="Logiciel d'affiliation." target="_blank">affilae</a> pour la gestion de votre programme d'affiliation.</b></p>
    <p>Le montant des commandes transmis lors des conversions est Hors Taxe et sans les frais de port.</p>
    <p>Le script de Tracking des conversions se d&eacute;clenche sur la page de confirmation de commande.</p>
    <p>Lorsqu'une commande contient plusieurs produits soumis &agrave; des r&egrave;gles de commissionnements diff&eacute;rentes,<br>
        elle g&eacute;n&egrave;re une conversion pour chacunes d'entre elles au montant des produits concern&eacute;s.</p>
</center>
<br/>
<br/>
<br/>

{if $successScript}
<div class="conf confirm">
    <img src="../img/admin/ok.gif" alt="" title="" />Votre script de s&eacute;curit&eacute; vient d'&ecirc;tre mis &agrave; jour !
</div>
{/if}

{if $successAdd}
<div class="conf confirm">
    <img src="../img/admin/ok.gif" alt="" title="" />Votre nouvelle r&egrave;gle de commissionnement a &eacute;t&eacute; ajout&eacute;e !
</div>
{/if}

{if $successEdit}
<div class="conf confirm">
    <img src="../img/admin/ok.gif" alt="" title="" />Votre r&egrave;gle de commissionnement a &eacute;t&eacute; mise &agrave; jour !
</div>
{/if}

{if $successDelete}
<div class="conf confirm">
    <img src="../img/admin/ok.gif" alt="" title="" />Votre r&egrave;gle de commissionnement a &eacute;t&eacute; supprim&eacute;e !
</div>
{/if}
<form action="" method="post" id="affilae-script">
    <fieldset class="width6">
        <legend><img src="../img/admin/cog.gif" alt="" class="middle" />Script de s&eacute;curit&eacute;</legend>
        <label for="script-input" style="width: inherit; padding-top: 10px">Script de s&eacute;curit&eacute;</label>
            <div class="margin-form" style="padding-left: 0;">
                <textarea name="affilae_script" id="" style="width: 100%; height: 180px; margin-bottom: 10px;">{if isset($script)}{$script}{/if}</textarea> <br />
                <center><input type="submit" value="Mettre &agrave; jour" class="btn primary" name="submitScriptAffilae" /></center>
            </div>
    </fieldset>
</form>
<br/>
<br/>
<br/>
{if $rules}
<table class="affilae zebra-striped">
    <thead>
        <tr>
            <th>R&egrave;gle</th>
            <th>Code de Tracking</th>
            <th>Cat&eacute;gories</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    {foreach from=$rules key=key item=rule}
    <tr>
        <td>{$rule.title}</td>
        <td>{$rule.code}</td>
        <td>
        {if $rule.has_cat == 'all'}Toutes
        {else}
        <ul style="margin: 0;">
            {foreach from=$rule.categories item=cat}
            <li>{$cat.name}</li>
            {/foreach}
        </ul>
        {/if}
        </td>
        <td>
            <a href="{$editLink}&rule={$key}" class="btn">Modifier</a> 
            <a href="{$deleteLink}&rule={$key}" class="btn" onclick="return confirm('Etes-vous sûr de vouloir supprimer cette règle de commissionnement ?');">Supprimer</a>
        </td>
    </tr>
    {/foreach}
    </tbody>
</table>
{/if}

<center><a href="{$addLink}" value="Ajouter une r&egrave;gle" class="btn primary">Ajouter une r&egrave;gle de commissionnement</a></center>
<br/>
<br/>
<br/>
<br/>
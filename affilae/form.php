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
</style>

<center>
    <img src="{$path}logo.png" alt="affilae, l'Affiliation pour tous. Cl&eacute; en main." />
</center>
<form action="" method="post">
    <fieldset class="width6">

    <legend><img src="../img/admin/cog.gif" alt="" class="middle" />Nouvelle r&egrave;gle de commissionnement</legend>

{if isset($titleError)}<div class="conf error"><img src="../img/admin/error.png" alt="" title="" />{$titleError}</div>{/if}

{if isset($codeError)}<div class="conf error"><img src="../img/admin/error.png" alt="" title="" />{$codeError}</div>{/if}

{if isset($catError)}<div class="conf error"><img src="../img/admin/error.png" alt="" title="" />{$catError}</div>{/if}

        <label for="">Titre de la r&egrave;gle</label>
        <div class="margin-form"><input type="text" name="affilae_title" {if isset($affilaeTitle)}value="{$affilaeTitle}"{/if} /></div>
        
        <label for="">Cl&eacute;</label>
        <div class="margin-form"><input type="text" name="affilae_code" {if isset($affilaeCode)}value="{$affilaeCode}"{/if} /> Format: xx-xx</div>
        
        <label for="">Cat&eacute;gories concern&eacute;es</label>
        <div class="margin-form">
            <select id="hasCat" name="affilae_has_categories" onChange="checkType();" style="width: 100px">
                <option value="all" {if isset($affilaeHasCategories) && $affilaeHasCategories == 'all'}selected="selected"{/if}>Toutes</option>
                <option value="choose" {if isset($affilaeHasCategories) && $affilaeHasCategories == 'choose'}selected="selected"{/if}>Choisir</option>
            </select>
            <div id="cat-table" style="display: none">
                <br>
                <table cellspacing="0" cellpadding="0" class="table" style="width: 600px;">
                <tr>
                        <th><input type="checkbox" name="checkme" class="noborder" onclick="checkDelBoxes(this.form, 'categoryBox[]', this.checked)" /></th>
                        <th>ID</th>
                        <th>Titre</th>
                </tr>
                {$cats}
                </table>
            </div>
        </div>
       
        <br>
        <div style="margin-left: 150px;">
            <input type="submit" value="Ajouter la r&egrave;gle" name="submitAffilae" class="btn primary" /> ou 
            <a href="{$cancelLink}" class="btn">Annuler</a>
        </div>
    </fieldset>
</form>
<script type="text/javascript">
    function checkType()
    {
        if($('#hasCat').val() == 'all') $('#cat-table').hide();
        else $('#cat-table').show();
    }

$(document).ready(function(){
    checkType();
});
</script>
{* Extend our master template *}
{extends file="master.tpl"}
{block name=body}
<div class="main-content">
<div class="main-content-inner">
<div class="page-content">
<!-- PAGE CONTENT BEGINS -->
    <h3 class="header smaller lighter blue">File tài liệu</h3>
    {if $type_use != 2 }
        <iframe allowfullscreen="" webkitallowfullscreen="" src="{site_url()}pdfviewer/web/viewer.php?url={site_url()}{$Url}&download=0" style="width: 100%; min-height: 800px;"> 
        </iframe>
    {else}
        <iframe allowfullscreen="" webkitallowfullscreen="" src="{site_url()}pdfviewer/web/viewer.php?url={site_url()}{$Url}&download=1" style="width: 100%; min-height: 800px;"> 
        </iframe>
    {/if}    
    
<!-- PAGE CONTENT ENDS -->
</div>
</div>
</div>
{/block}
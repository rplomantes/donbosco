<style>    
    #footer{
        position: fixed; 
        bottom:0px;
    }
    .pagenum:before {content: counter(page); }
    
</style>

@if(isset($chunk))
<div id="footer">Page <span class="pagenum"></span> of 
    @if(count($chunk->last()) <= ($group_count- 6))
    {{count($chunk)}}
    @else
    {{count($chunk)+1}}
    @endif
</div>
@else
<div id="footer">Page <span class="pagenum"></span></div>
@endif
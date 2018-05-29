<?php
use App\Http\Controllers\Accounts\AccountsController;


?>
Notes:
<ul type='circle'>
    @if(!$noAccount)
    <li>You still have a remaining balance of {{number_format(App\Ledger::TotalDue($idno),2)}}.</li>
    @endif
    @if((!$promoted) && $promotionFinal)
    <li>Please contact the Registrar's Office</li>
    @endif
    @if(!$promotionFinal)
    <li>We are still processing your information. This will be available as soon as we are done.</li>
    @endif
</ul>
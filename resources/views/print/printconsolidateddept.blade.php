<html>
<head>
     <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <style>
        .amount{text-align: right;}
        @page { margin:10px;padding:0px;margin-top: 100px;margin-bottom: 30px;}
        #header { position: fixed; left: 0px; top: -90px; right: 0px; height: 100px; text-align: center;font-size: 15px; }
        #footer { position: fixed; bottom:0px;border-top:1px solid gray;} .pagenum:before {content: counter(page); }
        table tr td{
            border-bottom: 1px solid;
            border-top: 1px solid;
            }
    </style>
    <body>    
        <div id="header"><span style="font-size:20px;">Don Bosco Technical School</span>
            <h4 style="text-align: center;margin-top:5px;margin-bottom: 0px;padding-bottom: 0px;font-size: 15pt;">CONSOLIDATED DEPARTMENTAL
                @if($accountcode == 4)
                INCOME
                @else
                EXPENSE
                @endif
                SUBSIDIARY LEDGER</h4>
            <p style="text-align: center;margin-top:5px;margin-bottom: 0px;padding-bottom: 0px;font-size: 12pt;">For the period of <span id="dates" ><b>{{date("F d, Y",strtotime($fromtran))}}</b> to <b>{{date("F d, Y",strtotime($totran))}}</b></span></p>
        </div>
        <div id="footer">Page <span class="pagenum"></span></div>
    <?php 
    use App\Http\Controllers\Accounting\DeptIncomeController;

    $totalnone = 0;
    $totalrector = 0;
    $totalservice = 0;
    $totaladmin = 0;
    $totalelem = 0;
    $totalhs = 0;
    $totaltvet = 0;
    $totalpastoral = 0;
    $abstotal = 0;
    ?>
    <table class="table table-striped" width="100%" cellspacing="0" style="font-size:13px">
        <thead>
            <tr>
            <th>ACCOUNT TITLE</th>
            <th>Total Income</th>
            <th>None</th>
            <th><a href="#">Rector</a></th>
            <th><a href="#">Student Services</a></th>
            <th><a href="#">Administration<br>Department</a></th>
            <th>Grade School<br>Department</th>
            <th>High School<br>Department</th>
            <th>TVET</th>
            <th>Pastoral <br>Office</th>
            </tr>
        </thead>

        @foreach($accounts as $account)
        <?php
            $credits = DB::Select("Select sum(none) as none,sum(rector) as rector,sum(elem) as elem,sum(hs) as hs,sum(tvet) as tvet,sum(service) as service,sum(admin) as admin,sum(pastoral) as pastoral from creditconsolidated where (transactiondate between '$fromtran' AND '$totran') and accountingcode = $account->acctcode");
            $debits  = DB::Select("Select sum(none) as none,sum(rector) as rector,sum(elem) as elem,sum(hs) as hs,sum(tvet) as tvet,sum(service) as service,sum(admin) as admin,sum(pastoral) as pastoral from debitconsolidated  where (transactiondate between '$fromtran' AND '$totran') and accountingcode = $account->acctcode");

            $none = 0;
            $rector= 0;
            $elem= 0;
            $hs= 0;
            $tvet= 0;
            $service= 0;
            $admin= 0;
            $pastoral= 0;

            $creditnone = 0;
            $creditrector= 0;
            $creditelem= 0;
            $crediths= 0;
            $credittvet= 0;
            $creditservice= 0;
            $creditadmin= 0;
            $creditpastoral= 0;

            $debitnone = 0;
            $debitrector= 0;
            $debitelem= 0;
            $debiths= 0;
            $debittvet= 0;
            $debitservice= 0;
            $debitadmin= 0;
            $debitpastoral= 0;

            $total = 0;

            if(count($credits)>0){
                $creditnone = $credits[0]->none;
                $creditrector= $credits[0]->rector;
                $creditelem= $credits[0]->elem;
                $crediths= $credits[0]->hs;
                $credittvet= $credits[0]->tvet;
                $creditservice= $credits[0]->service;
                $creditadmin= $credits[0]->admin;
                $creditpastoral= $credits[0]->pastoral;
            }

            if(count($debits)>0){
                $debitnone = $debits[0]->none;
                $debitrector= $debits[0]->rector;
                $debitelem= $debits[0]->elem;
                $debiths= $debits[0]->hs;
                $debittvet= $debits[0]->tvet;
                $debitservice= $debits[0]->service;
                $debitadmin= $debits[0]->admin;
                $debitpastoral= $debits[0]->pastoral;
            }
            if($accountcode == 4){
                $none = $creditnone - $debitnone;
                $rector= $creditrector - $debitrector;
                $elem= $creditelem - $debitelem;
                $hs= $crediths - $debiths;
                $tvet= $credittvet - $debittvet;
                $service= $creditservice - $debitservice;
                $admin= $creditadmin - $debitadmin;
                $pastoral= $creditpastoral - $debitpastoral;
            }else{
                $none =  $debitnone - $creditnone;
                $rector= $debitrector - $creditrector;
                $elem= $debitelem - $creditelem;
                $hs= $debiths - $crediths;
                $tvet= $debittvet - $credittvet;
                $service= $debitservice - $creditservice;
                $admin= $debitadmin - $creditadmin;
                $pastoral= $debitpastoral - $creditpastoral;
            }


            $total = $none + $rector + $elem + $hs + $tvet + $service + $admin + $pastoral;

            $totalnone = $totalnone + $none;
            $totalrector = $totalrector + $rector;
            $totalservice = $totalservice + $service;
            $totaladmin = $totaladmin + $admin;
            $totalelem = $totalelem + $elem;
            $totalhs = $totalhs + $hs;
            $totaltvet = $totaltvet + $tvet;
            $totalpastoral = $totalpastoral + $pastoral;
            $abstotal = $abstotal + $total;
        ?>

        <tr>
            <td>{{strtoupper($account->accountname)}}</td>
            <td class="amount">{{DeptIncomeController::returnzero($total)}}</td>
            <td class="amount">{{DeptIncomeController::returnzero($none)}}</td>
            <td class="amount">{{DeptIncomeController::returnzero($rector)}}</td>
            <td class="amount">{{DeptIncomeController::returnzero($service)}}</td>
            <td class="amount">{{DeptIncomeController::returnzero($admin)}}</td>
            <td class="amount">{{DeptIncomeController::returnzero($elem)}}</td>
            <td class="amount">{{DeptIncomeController::returnzero($hs)}}</td>
            <td class="amount">{{DeptIncomeController::returnzero($tvet)}}</td>
            <td class="amount">{{DeptIncomeController::returnzero($pastoral)}}</td>
        </tr>    
        @endforeach
        <tr>
            <td class="amount"><b>Grand Total</b></td>
            <td class="amount">{{DeptIncomeController::returnzero($abstotal)}}</td>
            <td class="amount">{{DeptIncomeController::returnzero($totalnone)}}</td>
            <td class="amount">{{DeptIncomeController::returnzero($totalrector)}}</td>
            <td class="amount">{{DeptIncomeController::returnzero($totalservice)}}</td>
            <td class="amount">{{DeptIncomeController::returnzero($totaladmin)}}</td>
            <td class="amount">{{DeptIncomeController::returnzero($totalelem)}}</td>
            <td class="amount">{{DeptIncomeController::returnzero($totalhs)}}</td>
            <td class="amount">{{DeptIncomeController::returnzero($totaltvet)}}</td>
            <td class="amount">{{DeptIncomeController::returnzero($totalpastoral)}}</td>
        </tr>
    </table>


    </body>
</html>



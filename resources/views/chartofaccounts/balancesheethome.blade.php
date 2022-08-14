
@extends('layouts.dashboard', ['title' => 'Balance Sheet'])
<?php

$getBal = new App\Models\AccountBalances();
$getOpenBal = new App\Models\OpenningAccountBalances();

?>
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <br><br><br>
        @include('inc.contentheader', ['title' => 'Current Month: '.date('F',strtotime($start_date)) ])
<br><br>
      <!-- Main content -->
      <section class="content">
            <div class="container-fluid">
              <!-- Small boxes (Stat box) -->
              <!-- /.row -->
              <!-- Main row -->
              <div class="row">
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable">
                    <div class="card card-primary">
                        <div class="card-header">
                            Current Month Stats:<b></b>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">


                            <div class="row">
                              <div class="col-md-12">
                                  <div class="panel-heading print_button">

                                  <div class="card-body">
                                      <div class="row">
                                          <div class="col-12 col-sm-12 assets_container">
                                            <table width="100%" border="0" class="table table-bordered table-striped">
                                                
                                                <tr>
                                                  <td>

                                                    <?php $total_rev = 0; $overall_rev = 0; $total_op_rev = 0; $total_op_non = 0; $total_op_non1 = 0;  ?>

                                                    @foreach ($account_group_revenue as $asset_group_infor )
                                                  <table width="100%" border="0">
                                                    @foreach ($asset_group_infor->ledger_categories as $ledger_category)
                                                   
                                                    @if (count($ledger_category->bank_accounts) != 0)
                                                    @foreach ($ledger_category->bank_accounts as $bank_account)
                                                    <?php if
                                                                            (number_format($getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date, $end_date)) != 0) { ?>
                                                    <?php $rev_exp = substr($bank_account->account_number, 0, 3);

                                                    //var_dump($lia_exp);

                                                    $revlevel = $rev_exp

                                                     ?>
                                                     @if('133' == $revlevel)
                                                     <?php

                                                     $total_rev = 0;


                                                      $total_op_rev = $total_op_rev + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date);

                                                      $total_rev = $total_op_rev;


                                                     ?>

                                                     @elseif('142' == $revlevel)
                                                     <?php

                                                     $total_rev = 0;

                                                     $total_op_non = $total_op_non + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date);

                                                     $total_rev = $total_op_non;


                                                     ?>
                                                     @elseif('145' == $revlevel)
                                                     <?php

                                                     $total_rev = 0;

                                                     $total_op_non1 = $total_op_non1 + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date);

                                                     $total_rev = $total_op_non1;


                                                     ?>
                                                     @endif
                                                    
                                                    <?php } ?>
                                                    @endforeach

                                                    @endif
                                                    @endforeach
                                                  </table>
                                                  @endforeach

                                                

                                               

                                                </td>
                                                  <td>
                                                    <?php $overall = 0; $totalexp1 = $totalexp2 = $totalexp3 = $totalexp4 = 0; $exp1 = 0; $exp2 = 0; $exp3 = 0; $exp4 = 0; ?>
                                                    @foreach ($account_group_expenses as $asset_group_infor )
                                                    <table width="100%" border="0">
                                                      @foreach ($asset_group_infor->ledger_categories as $ledger_category)
                                                     
                                                      @if (count($ledger_category->bank_accounts) != 0)
  
                                                      @foreach ($ledger_category->bank_accounts as $bank_account)
                                                      <?php $expl = substr($bank_account->account_number, 0, 2);
  
                                                      //var_dump($exp);
  
                                                      $explevel = $expl;
  
                                                       ?>
                                                       @if('21' == $explevel)
                                                       <?php
  
                                                       $totalexp1 = 0;
  
  
                                                        $exp1 = $exp1 + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date);
  
                                                        $totalexp1 = $exp1;
  
  
                                                       ?>
  
                                                       @elseif('22' == $explevel)
                                                       <?php
  
                                                       $totalexp2 = 0;
  
                                                       $exp2 = $exp2 + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date);
  
                                                       $totalexp2 = $exp2;
  
  
                                                       ?>

                                                        @elseif('26' == $explevel)
                                                        <?php

                                                        $totalexp3 = 0;

                                                        $exp3 = $exp3 + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date);

                                                        $totalexp3 = $exp3;


                                                        ?>

                                                        @elseif('28' == $explevel)
                                                            <?php

                                                            $totalexp4 = 0;

                                                            $exp4 = $exp4 + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                $bank_account->id, 1, $start_date);

                                                            $totalexp4 = $exp4;


                                                            ?>
  
                                                       @endif
                                                      
                                                      @endforeach

                                                      @endif
                                                      @endforeach
                                                    </table>
                                                    @endforeach
                                                    
                                                    
                                                   </td>
                                                </tr>
                                                <tr>
                                                  <td><b>CLASS 1: REVENUE: {{ number_format($total_rev) }}</b></td>
                                                  <td><b>CLASS 2 : EXPENSES: {{ number_format($totalexp1 + $totalexp2 + $totalexp3 + $totalexp4) }}</b></td>
                                                </tr>
                                                <tr>
                                                  <td></td>
                                                  <td>&nbsp;</td>
                                                </tr>
                                                
                                                <tr>
                                                  <td>
                                                    <?php $overall = 0; $totalass1 = $totalass2 = 0; $total_non = $total_current = 0; $total_op_non1 = 0; $total_other = 0; ?>
                                                    @foreach ($account_group_assets as $asset_group_infor )
                                                    <table width="100%" border="0">
                                                      @foreach ($asset_group_infor->ledger_categories as $ledger_category)
                                                     
                                                      @if (count($ledger_category->bank_accounts) != 0)
  
                                                      @foreach ($ledger_category->bank_accounts as $bank_account)
                                                      <?php $exp = substr($bank_account->account_number, 0, 2);
  
                                                      //var_dump($exp);
  
                                                      $assetlevel = $exp;
  
                                                       ?>
                                                       @if('31' == $assetlevel)
                                                       <?php
  
                                                       $totalass1 = 0;
  
  
                                                        $total_current = $total_current + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                $bank_account->id, 1, $start_date);
  
                                                        $totalass1 = $total_current;
  
  
                                                       ?>
  
                                                       @elseif('32' == $assetlevel)
                                                       <?php
  
                                                       $totalass2 = 0;
  
                                                       $total_non = $total_non + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                $bank_account->id, 1, $start_date);
  
                                                       $totalass2 = $total_non;
  
  
                                                       ?>
  
                                                       @endif
                                                      
                                                      @endforeach
                                                      
                                                      @endif
                                                      @endforeach
                                                    </table>
                                                    @endforeach
                                                  </td>
                                                  <td>
                                                    <?php $overall_lia = 0; $total_lia_current = 0; $total_lia_non = 0;  ?>
                                                    @foreach ($account_group_liabilities as $asset_group_infor )
                                                    <table width="100%" border="0">
                                                      @foreach ($asset_group_infor->ledger_categories as $ledger_category)
                                                      
                                                      @if (count($ledger_category->bank_accounts) != 0)
                                                      @foreach ($ledger_category->bank_accounts as $bank_account)
                                                      <?php $lia_exp = substr($bank_account->account_number, 0, 2);
  
                                                      //var_dump($lia_exp);
  
                                                      $lialevel = $lia_exp;
  
                                                       ?>
                                                       @if('41' == $lialevel)
                                                       <?php
  
                                                       $total_lia = 0;
  
  
                                                        $total_lia_current = $total_lia_current + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                $bank_account->id, 1, $start_date);
  
                                                        $total_lia = $total_lia_current;
  
  
                                                       ?>
  
  
                                                       @endif
  
                                                      
                                                      @endforeach
                                                      
                                                      @endif
                                                      @endforeach
                                                    </table>
                                                    @endforeach
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td><b>CLASS 3: ASSETS: {{ number_format($totalass2 + $totalass1) }}</b></td>
                                                  <td><b>CLASS 4: LIABILITIES: {{ number_format($total_lia) }}</b></td>
                                                </tr>
                                                <tr>
                                                  <td></td>
                                                  <td></td>
                                                </tr>
                                               
                                                <tr>
                                                  <td>
                                                    <?php $overall_equity = 0; $total_owners = 0; $total_ownerwquity = 0;?>
                                                    @foreach ($account_group_equity as $asset_group_infor )
                                                    <table width="100%" border="0">
                                                      @foreach ($asset_group_infor->ledger_categories as $ledger_category)
                                                      
                                                      @if (count($ledger_category->bank_accounts) != 0)
                                                      @foreach ($ledger_category->bank_accounts as $bank_account)
                                                      <?php $equi_exp = substr($bank_account->account_number, 0, 2);
  
                                                      //var_dump($lia_exp);
  
                                                      $equilevel = $equi_exp;
  
                                                       ?>
                                                       @if('51' == $equilevel)
                                                       <?php
  
                                                       $total_ownerwquity = 0;
  
  
                                                        $total_owners = $total_owners + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                $bank_account->id, 1, $start_date);
  
                                                        $total_ownerwquity = $total_owners;
  
  
                                                       ?>
  
                                                       @endif
  
                                                      
                                                      @endforeach
                                                      
                                                      @endif
                                                      @endforeach
                                                    </table>
                                                    @endforeach
                                                    
                                                  </td>
                                                  <td></td>
                                                </tr>
                                                <tr>
                                                  <td><b>CLASS 5 : RESERVES: {{ number_format($total_ownerwquity) }}</b></td>
                                                  <td>&nbsp;</td>
                                                </tr>
                                              </table>
                                            <br><br>
                                           

                                          </div>
                                        </div>
                                  </div>
                              </div>

                            </div>
                        </div>
                        <!-- /.card-body -->
                      </div>
                </section>
                <!-- /.Left col -->
              </div>
              <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
          </section>
          <!-- /.content -->
    </div>
    @include('inc.copyright')
@endsection

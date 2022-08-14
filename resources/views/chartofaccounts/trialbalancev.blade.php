@extends('layouts.dashboard', ['title' => 'Trial Balance'])
<?php

$getBal = new App\Models\AccountBalancesTrio();
$getOpenBal = new App\Models\OpenningAccountBalancesTrio();

?>
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <br><br>
      @include('inc.contentheader', ['title' => 'Trial Balance' ])
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <!-- /.row -->
                <!-- Main row -->
                <br>
                <br>
                <br>
                <div class="row">
                    <!-- Left col -->

                    <section class="col-lg-12 connectedSortable">
                        <div class="card card-primary">
                            <div class="card-header">
                              <b>Start:</b> {{ date('jS F Y',strtotime($start_date)) }} <b>End:</b>  {{ date('jS F Y',strtotime($end_date))  }}
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">


                                <div class="row">
                                    <div class="col-md-12">
                                        <br>
                                        <div class="panel-heading ">

                                        </div>

                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-sm-12 assets_container">
                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Account Number</th>
                                                                <th scope="col">Descritpion</th>
                                                                <th scope="col">Openning Balance</th>
                                                                <th scope="col">Activity Balance</th>
                                                                <th scope="col">Closing Balance</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $debit1 = 0;
                                                            $debit2 = 0;
                                                            $credit1 = 0;
                                                            $credit2 = 0;
                                                            $credit3 = 0;
                                                            $opendebit1 = 0;
                                                            $opendebit2 = 0;
                                                            $opencredit1 = 0;
                                                            $opencredit2 = 0;
                                                            $opencredit3 = 0;

                                                            $actdebit1 = 0;
                                                            $actdebit2 = 0;
                                                            $actcredit1 = 0;
                                                            $actcredit2 = 0;
                                                            $actcredit3 = 0;

                                                            $assets_total = 0;
                                                            $open_assets_total = 0;
                                                            $act_assets_total = 0;
                                                            $act_liabilities_total = 0;
                                                            $act_revenue_total = 0;
                                                            $act_expense_total = 0;
                                                            $act_equity_total = 0;
                                                            $open_revenue_total = 0;
                                                            $open_expense_total = 0;
                                                            $open_equity_total = 0;
                                                            ?>
                                                            @foreach ($account_group_assets as $asset_group_infor)
                                                                @foreach ($asset_group_infor->ledger_categories as $ledger_category)

                                                                    @if (count($ledger_category->bank_accounts) != 0)
                                                                        @foreach ($ledger_category->bank_accounts as $bank_account)
                                                                            <?php if
                                                                            (number_format($getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date, $end_date)) != 0) { ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <b>{{ $bank_account->account_number }}</b>
                                                                                </td>
                                                                                <td>
                                                                                    <?php
                                                                                    $sub_accounts =
                                                                                    DB::table('sub_bank_accounts')
                                                                                    ->select('sub_bank_accounts.*')
                                                                                    ->where('sub_bank_accounts.bank_account_id',
                                                                                    $bank_account->id)
                                                                                    ->get();
                                                                                    $bank_account_id =
                                                                                    Crypt::encrypt($bank_account->id);
                                                                                    ?>

                                                                                    @if (!$sub_accounts->isEmpty())
                                                                                       {{ $bank_account->name }}
                                                                                    @else
                                                                                        {{ $bank_account->name }}
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    <?php $openass = $getOpenBal->calculate_amount_on_accounts($bank_account->account_number, $bank_account->id, 1, $start_date); ?>
                                                                                    <b>{{ number_format($openass) }}
                                                                                </b>

                                                                            <?php $open_assets_total =
                                                                            $open_assets_total +
                                                                            $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date); ?></td>

                                                                                <td> <?php $actass = $getBal->calculate_amount_on_accounts($bank_account->account_number, $bank_account->id, 1, $start_date, $end_date); ?>
                                                                                    <b>{{ number_format($actass) }}
                                                                                        </b>

                                                                                    <?php $act_assets_total =
                                                                                    $act_assets_total +
                                                                                    $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                    $bank_account->id, 1, $start_date, $end_date); ?>
                                                                                </td>
                                                                                <td>

                                                                                   <b>{{ number_format( $openass + $actass ) }}</b>
                                                                                   <?php

                                                                                   $assets_total = $assets_total + $openass + $actass;

                                                                                   ?>

                                                                                </td>
                                                                            </tr>
                                                                            <?php } ?>
                                                                        @endforeach
                                                                        <?php $debit1 = $assets_total; $opendebit1 = $open_assets_total; $actdebit1 = $act_assets_total; ?>
                                                                    @endif
                                                                @endforeach
                                                            @endforeach
                                                            <?php $liabilities_total = 0; $open_liabilities_total = 0; ?>
                                                            @foreach ($account_group_liabilities as $asset_group_infor)
                                                                @foreach ($asset_group_infor->ledger_categories as $ledger_category)
                                                                    @if (count($ledger_category->bank_accounts) != 0)
                                                                        @foreach ($ledger_category->bank_accounts as $bank_account)
                                                                            <?php if
                                                                            (number_format($getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date, $end_date)) != 0) { ?>
                                                                            <tr>

                                                                                <td>
                                                                                    <b>{{ $bank_account->account_number }}</b>
                                                                                </td>
                                                                                <td>
                                                                                    <?php
                                                                                    $sub_accounts =
                                                                                    DB::table('sub_bank_accounts')
                                                                                    ->select('sub_bank_accounts.*')
                                                                                    ->where('sub_bank_accounts.bank_account_id',
                                                                                    $bank_account->id)
                                                                                    ->get();
                                                                                    $bank_account_id =
                                                                                    Crypt::encrypt($bank_account->id);
                                                                                    ?>

                                                                                    @if (!$sub_accounts->isEmpty())
                                                                                        {{ $bank_account->name }}
                                                                                    @else
                                                                                        {{ $bank_account->name }}
                                                                                    @endif
                                                                                </td>

                                                                                <td>

                                                                                    <?php $open_liabilities_total
                                                                                    = $open_liabilities_total +
                                                                                    $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                    $bank_account->id, 1, $start_date);

                                                                                    ?>

                                                                                    <?php $openlib = $getOpenBal->calculate_amount_on_accounts($bank_account->account_number, $bank_account->id, 1, $start_date); ?>

                                                                                    <b>{{ number_format($openlib) }}
                                                                                        </b>


                                                                                </td>
                                                                                <td>
                                                                                    <?php $act_liabilities_total
                                                                                    = $act_liabilities_total +
                                                                                    $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                    $bank_account->id, 1, $start_date, $end_date);

                                                                                    ?>

                                                                                    <?php $actlib = $getBal->calculate_amount_on_accounts($bank_account->account_number, $bank_account->id, 1, $start_date, $end_date); ?>

                                                                                    <b>{{ number_format($actlib) }}
                                                                                        </b>
                                                                                </td>
                                                                                <td>

                                                                                    <b>{{ number_format( $openlib + $actlib ) }}</b>
                                                                                    <?php

                                                                                    $liabilities_total = $liabilities_total + $openlib + $actlib;

                                                                                    ?>



                                                                                </td>
                                                                            </tr>
                                                                            <?php } ?>
                                                                        @endforeach
                                                                        <?php $credit1 = $liabilities_total; $opencredit1 = $open_liabilities_total; $actcredit1 = $act_liabilities_total;
                                                                        ?>
                                                                    @endif
                                                                @endforeach
                                                            @endforeach
                                                            <?php $revenue_total = 0; ?>
                                                            @foreach ($account_group_revenue as $asset_group_infor)
                                                                @foreach ($asset_group_infor->ledger_categories as $ledger_category)
                                                                    @if (count($ledger_category->bank_accounts) != 0)
                                                                        @foreach ($ledger_category->bank_accounts as $bank_account)
                                                                            <?php if
                                                                            (number_format($getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date, $end_date)) != 0) { ?>
                                                                            <tr>
                                                                                <td>

                                                                                    <b>{{ $bank_account->account_number }}</b>
                                                                                </td>
                                                                                <td>
                                                                                    <?php
                                                                                    $sub_accounts =
                                                                                    DB::table('sub_bank_accounts')
                                                                                    ->select('sub_bank_accounts.*')
                                                                                    ->where('sub_bank_accounts.bank_account_id',
                                                                                    $bank_account->id)
                                                                                    ->get();
                                                                                    $bank_account_id =
                                                                                    Crypt::encrypt($bank_account->id);
                                                                                    ?>

                                                                                    @if (!$sub_accounts->isEmpty())
                                                                                        {{ $bank_account->name }}
                                                                                    @else
                                                                                        {{ $bank_account->name }}
                                                                                    @endif
                                                                                </td>
                                                                                <td> <?php $openrev = $getOpenBal->calculate_amount_on_accounts($bank_account->account_number, $bank_account->id, 1, $start_date); ?>
                                                                                    <b>{{ number_format($openrev) }}
                                                                                    </b>

                                                                                <b>

                                                                                </b>

                                                                                <?php $open_revenue_total =
                                                                                $open_revenue_total +
                                                                                $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                $bank_account->id, 1, $start_date); ?>
                                                                                </td>

                                                                                <td>
                                                                                    <?php  $actrev = $getBal->calculate_amount_on_accounts($bank_account->account_number, $bank_account->id, 1, $start_date, $end_date);  ?>

                                                                                    <b>{{ number_format($actrev) }}
                                                                                    </b>

                                                                                <b>

                                                                                </b>

                                                                                <?php $act_revenue_total =
                                                                                $act_revenue_total +
                                                                                $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                $bank_account->id, 1, $start_date, $end_date); ?>

                                                                                </td>
                                                                                <td>
                                                                                    <b>{{ number_format( $openrev + $actrev ) }}</b>
                                                                                    <?php

                                                                                    $revenue_total = $revenue_total + $openrev + $actrev;

                                                                                    ?>
                                                                                </td>
                                                                            </tr>
                                                                            <?php } ?>
                                                                        @endforeach
                                                                        <?php $credit2 = $revenue_total; $opencredit2 = $open_revenue_total; $actcredit2 = $act_revenue_total ?>
                                                                    @endif
                                                                @endforeach
                                                            @endforeach
                                                            <?php $expense_total = 0; ?>
                                                            @foreach ($account_group_expenses as $asset_group_infor)
                                                                @foreach ($asset_group_infor->ledger_categories as $ledger_category)
                                                                    @if (count($ledger_category->bank_accounts) != 0)
                                                                        @foreach ($ledger_category->bank_accounts as $bank_account)
                                                                            <?php if
                                                                            (number_format($getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date, $end_date) != 0) != 0) { ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <b>{{ $bank_account->account_number }}</b>
                                                                                </td>
                                                                                <td>
                                                                                    <?php
                                                                                    $sub_accounts =
                                                                                    DB::table('sub_bank_accounts')
                                                                                    ->select('sub_bank_accounts.*')
                                                                                    ->where('sub_bank_accounts.bank_account_id',
                                                                                    $bank_account->id)
                                                                                    ->get();
                                                                                    $bank_account_id =
                                                                                    Crypt::encrypt($bank_account->id);
                                                                                    ?>

                                                                                    @if (!$sub_accounts->isEmpty())
                                                                                       {{ $bank_account->name }}
                                                                                    @else
                                                                                        {{ $bank_account->name }}
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    <?php $openex = $getOpenBal->calculate_amount_on_accounts($bank_account->account_number, $bank_account->id, 1, $start_date); ?>
                                                                                    <b>{{ number_format($openex) }}
                                                                                    </b>

                                                                                <?php $open_expense_total =
                                                                                $open_expense_total +
                                                                                $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                $bank_account->id, 1, $start_date); ?>
                                                                                </td>

                                                                                <td>
                                                                                    <?php $exp_act = $getBal->calculate_amount_on_accounts($bank_account->account_number, $bank_account->id, 1, $start_date, $end_date); ?>
                                                                                    <b>{{ number_format($exp_act) }}
                                                                                        </b>

                                                                                    <?php $act_expense_total =
                                                                                    $act_expense_total +
                                                                                    $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                    $bank_account->id, 1, $start_date, $end_date); ?>


                                                                                </td>
                                                                                <td>
                                                                                    <b>{{ number_format( $openex + $exp_act ) }}  </b>
                                                                                    <?php

                                                                                    $expense_total = $expense_total + $openex + $exp_act;
                                                                                    ?>
                                                                                </td>
                                                                            </tr>
                                                                            <?php } ?>
                                                                        @endforeach
                                                                        <?php $debit2 = $expense_total;  $opendebit2 = $open_expense_total; $actdebit2 = $act_expense_total; ?>
                                                                    @endif
                                                                @endforeach
                                                            @endforeach
                                                            <?php $equity_total = 0; ?>
                                                            @foreach ($account_group_equity as $asset_group_infor)
                                                                @foreach ($asset_group_infor->ledger_categories as $ledger_category)
                                                                    @if (count($ledger_category->bank_accounts) != 0)
                                                                        @foreach ($ledger_category->bank_accounts as $bank_account)
                                                                            <?php if
                                                                            (number_format($getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date, $end_date) != 0) != 0) { ?>

                                                                            <tr>
                                                                                <td>
                                                                                    <b>{{ $bank_account->account_number }}</b>
                                                                                </td>
                                                                                <td>
                                                                                    <?php
                                                                                    $sub_accounts =
                                                                                    DB::table('sub_bank_accounts')
                                                                                    ->select('sub_bank_accounts.*')
                                                                                    ->where('sub_bank_accounts.bank_account_id',
                                                                                    $bank_account->id)
                                                                                    ->get();
                                                                                    $bank_account_id =
                                                                                    Crypt::encrypt($bank_account->id);
                                                                                    ?>

                                                                                    @if (!$sub_accounts->isEmpty())
                                                                                        {{ $bank_account->name }}
                                                                                    @else
                                                                                        {{ $bank_account->name }}
                                                                                    @endif
                                                                                </td>

                                                                                <td>
                                                                                    <?php $openeq = $getOpenBal->calculate_amount_on_accounts($bank_account->account_number, $bank_account->id, 1, $start_date); ?>
                                                                                    <b>{{ number_format($openeq) }}
                                                                                    </b>

                                                                                <?php $open_equity_total =
                                                                                $open_equity_total +
                                                                                $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                $bank_account->id, 1, $start_date); ?>
                                                                                </td>
                                                                                <td>

                                                                                    <?php $acteq = $getBal->calculate_amount_on_accounts($bank_account->account_number, $bank_account->id, 1, $start_date, $end_date); ?>

                                                                                    <b>{{ number_format($acteq) }}
                                                                                    </b>

                                                                                <?php $act_equity_total =
                                                                                $act_equity_total +
                                                                                $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                $bank_account->id, 1, $start_date, $end_date); ?>

                                                                                </td>
                                                                                <td>

                                                                                    <b>{{ number_format( $openeq + $acteq ) }}</b>
                                                                                    <?php

                                                                                    $equity_total = $equity_total + $openeq + $acteq;



                                                                                    ?>
                                                                                </td>
                                                                            </tr>
                                                                            <?php } ?>
                                                                        @endforeach
                                                                        <?php $credit3 = $equity_total; $opencredit3 = $open_equity_total; $actcredit3 = $act_equity_total; ?>
                                                                    @endif
                                                                @endforeach
                                                            @endforeach

                                                            <!-- <tr>
                                                                <td>
                                                                    <h5><b> Total </b></h5>
                                                                </td>
                                                                <td>

                                                                </td>

                                                                <td> {{ number_format($opencredit1 + $opencredit2 + $opencredit3 + $opendebit1 + $opendebit2) }}</td>
                                                                <td>
                                                                    {{ number_format( $actcredit1 + $actcredit2 + $actcredit3 + $actdebit1 + $actdebit2) }}
                                                                </td>
                                                                <td>
                                                                    {{ number_format($credit1 + $credit2 + $credit3 + $debit1 + $debit2) }}
                                                                </td>
                                                            </tr>  -->
                                                        </tbody>
                                                    </table>
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
    <br><br><br><br><br><br><br>
    @include('inc.copyright')
@endsection



                                       

                                          <table id="" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th><b>Item Code</b></th>
                                                    <th><?php

                                                        $get_account = DB::table('bank_accounts')
                                                                    ->select('bank_accounts.*')
                                                                    ->where('account_number',  $account)
                                                                    ->first();

                                                        $get_sub_accounth = DB::table('sub_bank_accounts')
                                                                    ->select('sub_bank_accounts.*')
                                                                    ->where('account_number',  $account)
                                                                    ->first();

                                                        $get_more_accountsh = DB::table('more_account_belows')
                                                                    ->select('more_account_belows.*')
                                                                    ->where('account_number',  $account)
                                                                    ->first();

                                                        $get_last_accountsh = DB::table('last_bank_accounts')
                                                                    ->select('last_bank_accounts.*')
                                                                    ->where('account_number',  $account)
                                                                    ->first();

                                                        if(!empty($get_accounth->name)){
                                                        echo $get_accounth->name;
                                                        }

                                                        if(!empty($get_sub_accounth->name)){
                                                        echo $get_sub_accounth->name;
                                                        }

                                                        if(!empty($get_more_accountsh->name)){
                                                        echo $get_more_accountsh->name;
                                                        }

                                                        if(!empty($get_last_accountsh->name)){
                                                        echo $get_last_accountsh->name;
                                                        }

                                                    ?></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                  </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                   
                                                    <th></th>

                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                  </tr>
                                            </thead>
                                            <thead>
                                                <tr>
                                                    <th><b>Date</b></th>
                                                    <th><b>Account Name</b></th>
                                                    <th><b>Description</b></th>
                                                    <th><b>Requested By</b></th>
                                                    <th><b>Category</b></th>
                                                    <th><b>Amount Disbursed</b></th>
                                                  </tr>
                                            </thead>
                                            <tbody>
                                                     <tr style="color: red;">
                                                        <td>Start Date: {{ date('F d, Y',strtotime($start_date)) }} </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><?php $totalBal = 0; ?></td>
                                                     </tr>
                                                @foreach($account_journal_entries as $entry)
                                                    <tr>
                                                        <td>{{ date('F d, Y',strtotime($entry->created_at)) }}</td>
                                                        <td>
                                                            <?php

                                                        $entry_account = DB::table('general_ledgers')
                                                                    ->select('general_ledgers.*')
                                                                    ->where('general_ledgers.reference', $entry->reference)
                                                                    ->where('general_ledgers.account', '!=', $entry->account)
                                                                    ->first();

                                                        $get_account = DB::table('bank_accounts')
                                                                    ->select('bank_accounts.*')
                                                                    ->where('account_number',  $entry_account->account)
                                                                    ->first();

                                                        $get_sub_account = DB::table('sub_bank_accounts')
                                                                    ->select('sub_bank_accounts.*')
                                                                    ->where('account_number',  $entry_account->account)
                                                                    ->first();

                                                        $get_more_accounts = DB::table('more_account_belows')
                                                                    ->select('more_account_belows.*')
                                                                    ->where('account_number',  $entry_account->account)
                                                                    ->first();

                                                        $get_last_accounts = DB::table('last_bank_accounts')
                                                                    ->select('last_bank_accounts.*')
                                                                    ->where('account_number',  $entry_account->account)
                                                                    ->first();

                                                        if(!empty($get_account->name)){
                                                        echo $get_account->name;
                                                        }

                                                        if(!empty($get_sub_account->name)){
                                                        echo $get_sub_account->name;
                                                        }

                                                        if(!empty($get_more_accounts->name)){
                                                        echo $get_more_accounts->name;
                                                        }

                                                        if(!empty($get_last_accounts->name)){
                                                        echo $get_last_accounts->name;
                                                        }

                                                            ?>
                                                        </td>
                                                        <td>{{ $entry->details }}</td>

                                                        <td>{{ $entry->requested_by }}</td>
                                                        <td>{{ $entry->category }}</td>
                                                        <td>
                                                            @if ($entry->debit == null)

                                                            @else
                                                                {{ number_format($entry->debit) }}
                                                            @endif
                                                            @if ($entry->credit == null)

                                                            @else
                                                                {{ number_format($entry->credit) }}
                                                            @endif
                                                            <?php
                                                                if($entry->debit == null){
                                                                  $totalBal = $totalBal + $entry->credit ;
                                                                }else{
                                                                     $totalBal = $totalBal + $entry->debit ;
                                                                }

                                                            ?>
                                                        </td>
                                                       

                                                    </tr>
                                                    <?php $totalBal = $totalBal; ?>
                                                @endforeach
                                                <tr style="color: red;">
                                                    <td>End Date: {{ date('F d, Y',strtotime($end_date)) }}</td>
                                                    <td><b>Total:</b></td>
                                                    <td></td>
                                                    <td><b>{{ number_format( $totalBal ) }}</b></td>
                                                 </tr>
                                                </tbody>
                                          </table>
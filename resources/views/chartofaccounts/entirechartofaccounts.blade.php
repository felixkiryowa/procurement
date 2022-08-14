@extends('layouts.dashboard')

@section('content')
<div class="content-wrapper">
<!-- Main content -->
<section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-12 connectedSortable">
              <br>
              <br>
              <div class="card card-primary">
                  <div class="card-header">
                     <h5>Entire Chart Of Accounts</h5>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                      <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-12 assets_container">
                                        <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>PARENT</th>
                                                <th>SUB</th>
                                                <th>SUB-SUB</th>
                                                <th>SUB-SUB-SUB</th>
                                                <th>SUB-SUB-SUB-SUB</th>
                                                <th>SUB-SUB-SUB-SUB-SUB</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                              @foreach ($account_group as $asset_group_infor )
                                              <tr>
                                                  <td><b>{{ $asset_group_infor->name }}</b></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                              </tr>
                                              @foreach ($asset_group_infor->ledger_categories as $ledger_category)
                                              <tr>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                              </tr>
                                              <tr>
                                                  <td></td>
                                                  <td>{{ $ledger_category->item_code }} {{ $ledger_category->name }}</td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                                  <td></td>
                                              </tr>
                                              @foreach ($ledger_category->bank_accounts as $bank_account)
                                               <tr>
                                                   <td></td>
                                                   <td></td>
                                                   <td>{{ $bank_account->account_number }} {{ $bank_account->name }}</td>
                                                   <td></td>
                                                   <td></td>
                                                   <td></td>
                                               </tr>
                                               @foreach ($bank_account->sub_bank_accounts as $sub_bank_account)
                                                  <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ $sub_bank_account->account_number }} {{ $sub_bank_account->name }}</td>
                                                    <td></td>
                                                    <td></td>
                                                  </tr>
                                                  @foreach ($sub_bank_account->accounts_below_sub_account as $accounts_below_sub_account)
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>{{ $accounts_below_sub_account->account_number }} {{ $accounts_below_sub_account->name }}</td>
                                                        <td></td>
                                                    </tr>
                                                    @foreach ($accounts_below_sub_account->more_accounts_below as $more_account_below)
                                                       <tr>
                                                           <td></td>
                                                           <td></td>
                                                           <td></td>
                                                           <td></td>
                                                           <td></td>
                                                           <td>{{ $more_account_below->account_number }} {{ $more_account_below->name }}</td>
                                                       </tr>
                                                    @endforeach

                                                  @endforeach
                                                 @endforeach
                                              @endforeach
                                              @endforeach
                                              @endforeach
                                            </tbody>
                                          </table>
                                        </div>
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

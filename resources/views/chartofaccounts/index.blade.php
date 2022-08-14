
@extends('layouts.dashboard', ['title' => 'Journal Ledger Entries'])

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Main content -->
<!-- Main content -->
    <section class="content">

        <!-- /.row -->
        <!-- Main row -->
        <div class="">
            <br><br>
            @include('inc.messages')
          <!-- Left col -->

          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-12 connectedSortable">

            <!-- Map card -->
            <div class="card">
              <div class="card-header no-border">
                <h3 class="card-title">
                  All Entries
                </h3>
                <!-- card tools -->

                <!-- /.card-tools -->
              </div>
              <div class="card-body">


                 <br>

              <br><br><br>

              <div class="table-responsive">
                  <table id="dataTable2" width="100%" class="table-bordered table-striped">
                      <thead>
                      <tr>
                        <th>ID</th>
                        <th>Details</th>
                        <th>Item Code</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Requested By</th>
                        <th>Category</th>
                        <th>Reference</th>
                        <th>Date</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($entries as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->details }}</td>
                            <td>{{ $item->account }}</td>
                            <td>{{ $item->debit }}</td>
                            <td>{{ $item->credit }}</td>
                            <td>{{ $item->requested_by }}</td>
                            <td>{{ $item->category }}</td>
                            <td>{{ $item->reference }}</td>
                            <td>{{ $item->created_at }}</td>
                            
                        </tr>
                        @endforeach
                       </tbody>

                    </table>
              </div>
            </div>
            <!-- /.card -->


        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.create modal -->

            <!-- /.card -->
          </section>
          <!-- right col -->
        </div>

    <!-- /.content -->




    @include('inc.copyright')
@endsection

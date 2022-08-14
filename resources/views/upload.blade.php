<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<form method="POST" 
action="{{url('upload')}}"
                            class="panel-heading"  enctype="multipart/form-data">
@csrf
    <div class="row all_entries_row">
            <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputPassword1">Upload Beneficiaries Excel File</label>
                <input type="file" class="form-control" required  name="uploaded_attachment" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,text/comma-separated-values, text/csv, application/csv">
                <div id="filename_error"></div>
            </div>
            </div>
            <div class="col-md-3">
                <button type="submit" name="action" class="btn btn-success btn-sm journal-search-btn" value="excel">Upload</button>
            </div>
        </div>
</form>
    
</body>
</html>
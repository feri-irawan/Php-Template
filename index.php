<?php
$api_key = "b0b6beed4cef4b155d14a9514fafd24e9797e3e075f81f488bc20fc53153a900";
$courier = $_GET["courier"];
$no_resi = $_GET["resi"];
$params  = "api_key=$api_key&courier=$courier&awb=$no_resi";

$url_api = "https://api.binderbyte.com/v1/track?$params";
$file = file_get_contents($url_api);
$file = json_decode($file, true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Cek Resi</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

<style>
.box-circle {
  display: inline-block;
  border-radius: 20px;
  background: #fff;
  padding: .5px .5rem;
  color: var(--bs-primary);
  margin-right: 1rem;
}
.card-header {
  display: flex;
  align-items: center;
}
</style>
</head>
<body>
  
<div class="container">
  <form action="" method="get" accept-charset="utf-8">
   <div class="row">
     <div class="col m-3">
      <input class="form-control mb-3" type="text" name="resi" placeholder="Masukan No. Resi..."/>
      <select name="courier" class="form-select mb-3">
        <option value="null">Pilih Courier</option>
        <?php
        $listCourier = json_decode(file_get_contents("https://api.binderbyte.com/v1/list_courier?api_key=$api_key"), true);
        foreach ($listCourier as $rowCourier): ?>
          <option value="<?=$rowCourier["code"]?>"><?=$rowCourier["description"]?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-primary" type="submit">Cek</button>
     </div>
   </div>
  </form>
  
<?php if ($file["status"] == 200): ?>
  <?php
  
  function info($type, $where) {
    global $file;
    $where = $file["data"][$type][$where];
  
    if ($type == "summary" || $type == "detail") {
      return ($where == "") ? "-" : $where;
    }
  }
  
  //History Function
  $history = $file["data"]["history"];
  function history($forWhat) {
    global $history;
    $forWhat = $history[$forWhat];
    return ($forWhat == "") ? "-" : $forWhat;
  }
  ?>
  <table class="table table-bordered table-striped">
    <tr>
      <th colspan="2" class="text-center">Ringkasan</th>
    </tr>
    <tr>
      <td>Resi</td>
      <td><?= info("summary", "awb") ?></td>
    </tr>
    <tr>
      <td>Kurir</td>
      <td><?= info("summary", "courier") ?></td>
    </tr>
    <tr>
      <td>Layanan</td>
      <td><?= info("summary", "service") ?></td>
    </tr>
    <tr>
      <td>Status</td>
      <td><?= info("summary", "status") ?></td>
    </tr>
    <tr>
      <td>Tanggal</td>
      <td><?= info("summary", "date") ?></td>
    </tr>
    <tr>
      <td>Deskripsi</td>
      <td><?= info("summary", "desc") ?></td>
    </tr>
    <tr>
      <td>Harga</td>
      <td><?= info("summary", "amount") ?></td>
    </tr>
    <tr>
      <td>Bobot</td>
      <td><?= info("summary", "weight") ?></td>
    </tr>
  </table>
  <table class="table table-bordered table-striped">
    <tr>
      <th colspan="2" class="text-center">Detail</th>
    </tr>
    <tr>
      <td>Dari</td>
      <td><?= info("detail", "origin") ?></td>
    </tr>
    <tr>
      <td>Tujuan</td>
      <td><?= info("detail", "destination") ?></td>
    </tr>
    <tr>
      <td>Pengirim</td>
      <td><?= info("detail", "shipper") ?></td>
    </tr>
    <tr>
      <td>Penerima</td>
      <td><?= info("detail", "receiver") ?></td>
    </tr>
  </table>
  
  <h1>Riwayat</h1>
    <?php $i = 0; foreach ($history as $row): $i++ ?>
      <div class="card mb-3">
        <div class="card-header bg-primary text-white">
          <div class="box-circle"><?=$i?></div>
          <?=$row["date"];?>
        </div>
        <div class="card-body"><?=$row["desc"]?></div>
        <div class="card-footer">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 0c-5.522 0-10 4.395-10 9.815 0 5.505 4.375 9.268 10 14.185 5.625-4.917 10-8.68 10-14.185 0-5.42-4.478-9.815-10-9.815zm0 18c-4.419 0-8-3.582-8-8s3.581-8 8-8 8 3.582 8 8-3.581 8-8 8zm5-9.585l-5.708 5.627-3.706-3.627 1.414-1.415 2.291 2.213 4.295-4.213 1.414 1.415z"/></svg>
          <?=history("location")?>
        </div>
      </div>
    <?php endforeach; ?>
  </table>
<?php endif; ?>
  
  
</div>
<!-- endContainer -->


  <pre>
    <code>
      <?php var_dump($file);?>
    </code>
  </pre>
</body>
</html>
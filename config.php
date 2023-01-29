<?php

$info = 'https://bitinfocharts.com/comparison/price-mining_profitability-'.($coin!='none'?$coin:'btc').'.html#1y';
$p0 = strlen($_POST['p0'])? $_POST['p0'] : 2000;
$p1 = strlen($_POST['p1'])? $_POST['p1'] : $p0 * 1.5;
$hf = strlen($_POST['hf'])? $_POST['hf'] : 1;
$dph = strlen($_POST['dph'])? $_POST['dph'] : 0.1;
$mh = strlen($_POST['mh'])? $_POST['mh'] : 500;
$mp = strlen($_POST['mp'])? $_POST['mp'] : 1000;
$mc = strlen($_POST['mc'])? $_POST['mc'] : 5000;
$kwh = strlen($_POST['kwh'])? $_POST['kwh'] : 0.05;
$coins = array('btc', 'ltc', 'dash');
foreach ($coins as $c) $$c = preg_split('/,\s*/', rtrim(file_get_contents("coins/$c")));
array_push($btc, 110, 3250, 2250, 'BTC', 'TH', array_shift($btc));
array_push($ltc, 9.5, 3425, 6500, 'LTC', 'GH', array_shift($ltc));
array_push($dash, 440, 2200, 800, 'DASH', 'GH', array_shift($dash));
$miners = <<< END
<div>Miners:</div>
<div>Bitcoin - Bitmain Antminer S19 Pro, {$btc[2]}{$btc[6]}/s, {$btc[3]}W</div>
<div>Litecoin - Bitmain Antminer L7, {$ltc[2]}{$ltc[6]}/s, {$ltc[3]}W</div>
<div>Dash - StrongU STU-U6, {$dash[2]}{$dash[6]}/s, {$dash[3]}W</div>
END;

?>

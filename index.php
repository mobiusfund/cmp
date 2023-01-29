<!-- Copyright (c) 2021, Jake Fan, Mobius Fund -->
<?php

$years = $_GET['years'] + 0;
if (!$years or $years > 5) header("location:".dirname($_SERVER['PHP_SELF']).'/?years=1');
$disp = strlen($_POST['disp'])? $_POST['disp'] : 'none';
$coin = strlen($_POST['coin'])? $_POST['coin'] : 'none';
include('config.php');
$xd = 365 * $years;

$p0_at = $p0 == ${$coin}[0] || $p0 == round(${$coin}[0]);
$dph_at = $dph == ${$coin}[1];
if ($p0_at && $dph_at) $p0_at = $dph_at = 'coin-at';
else $coin = 'none';
if ($coin != 'none') {
    $p0 = $p0 < 10? number_format($p0, 2) : round($p0);
    $p1 = $p1 < 10? number_format($p1, 2) : round($p1);
    $dph = number_format($dph, 4);
}

$pri = array();
$pro = array();
$k1 = log($p1 / $p0 * 1) / $xd;
$k = log($p1 / $p0 / $hf) / $xd;
for ($x = 0; $x <= $xd; $x++) {
    $pri[] = $p0 * exp($k1 * $x);
    $pro[] = $dph * exp($k * $x) * $mh - $kwh * 24 * $mp / 1000;
    //echo number_format($pri[$x], 2), ', ';
}
$sum = array_sum($pro);
$tr = round(($p1 - $p0) / $p0 * 100) . '%, $' . round($p1 / $p0 * $mc);
$mr = round(($sum - $mc) / $mc * 100) . '%, $' . round($sum);

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Crypto Mining Profit Calculator</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/widget.css">
<style>
    body {overscroll-behavior:contain;}
    #acww-formwrapper {height:auto; overflow:auto;}
    #acww-header {text-indent:0; text-align:center; font-size:14px; padding-top:14px; background-color:#ccc;}
    .info-bullet {width:18px; color:#999; vertical-align:top;}
    .info-button {float:right; color:#37b; font-size:14px; font-weight:700; cursor:pointer;}
    .coin-off {display:inline-block; font-size:12px; background:#999; color:#fff; padding:0 6px; margin:0; border-radius:3px; cursor:pointer;}
    .coin-on {background:#37b;}
    .coin-at {text-decoration:underline;}
    .form-group {margin-bottom: 13px;}
    .glyphicon b {font-weight:1000;}
    .var-label {border:none; text-align:right; font-weight:700; cursor:default !important;}
    .roi-output {font-weight:700; padding:6px 0 6px 10px;}
</style>
<script>
function incyears() {
    location.href.match(/(.*\byears=)(\d+)(\b.*)/);
    years = parseInt(RegExp.$2) + 1;
    if (years > 5) years = 1;
    form = document.getElementById("acww-formwrapper");
    p1 = form.elements['p0'].value * (1 + years * 0.5);
    form.elements['p1'].value = p1 < 10? p1.toFixed(2) : p1.toFixed(0);
    form.elements['hf'].value = (75 + years * 25) / 100;
    form.action = RegExp.$1 + years + RegExp.$3;
    form.submit();
}
function showinfo(show) {
    disp = document.getElementById("disp");
    button = document.getElementById("info-popup");
    toggle = {"none": "block", "block": "none"};
    if (show) button.style.display = toggle[button.style.display];
    else button.style.display = "none";
    disp.value = button.style.display;
}
function showcoin(bttn) {
    vals = {
<?php foreach ($coins as $c) {
    $a = '';
    foreach(array_slice($$c, 0, 5) as $v) $a .= "$v, ";
    echo "'$c': [$a],\n";
}?>
        'none': [,,,,,],
    }
    location.href.match(/(.*\byears=)(\d+)(\b.*)/);
    if (bttn == '<?=$coin;?>') bttn = 'none';
    p0 = vals[bttn][0];
    p1 = p0 * 1.5;
    dph = vals[bttn][1];
    form = document.getElementById("acww-formwrapper");
    form.elements['p0'].value = !p0?  '' : p0 < 10? p0.toFixed(2) : p0.toFixed(0);
    form.elements['p1'].value = !p1?  '' : p1 < 10? p1.toFixed(2) : p1.toFixed(0);
    form.elements['hf'].value = 1;
    form.elements['dph'].value = !dph? '' : dph.toFixed(4);
    form.elements['mh'].value = vals[bttn][2];
    form.elements['mp'].value = vals[bttn][3];
    form.elements['mc'].value = vals[bttn][4];
    form.elements['coin'].value = bttn;
    form.action = RegExp.$1 + 1 + RegExp.$3;
    form.submit();
}
</script>
    </head>
    <body class="vsc-initialized">
        <div id="acww-widget-iframeinner">
            <div id="acww-piechart" class="highcharts-container col-xs-12 col-sm-4 no-pad" style="height:auto; margin:auto; float:none; border:1px solid #eee;">
<div id="info-popup" style="padding:6px 12px; font-size:12px; display:<?=$disp;?>;">
<div>Usage: <div class="info-button" style="padding-left:6px;" onclick="showinfo(0);">X</div></div>
<table>
<tr><td class="info-bullet">&#9632;</td><td>Press Enter to input a number and recalculate</td></tr>
<tr><td class="info-bullet">&#9632;</td><td>Click a coin symbol to populate with real-world data</td></tr>
<tr><td class="info-bullet">&#9632;</td><td>Click Price Year 1 to iterate thru 5</td></tr>
</table>
<div>&nbsp;</div>
<div>Background:</div>
<table>
<tr><td class="info-bullet">&#9632;</td><td>Assumes exponential growth or decay, from Price Today to Price Year N</td></tr>
<tr><td class="info-bullet">&#9632;</td><td>Assumes selling at spot</td></tr>
<tr><td class="info-bullet">&#9632;</td><td>Daily Revenue = $/Hash * Miner Hash</td></tr>
<tr><td class="info-bullet">&#9632;</td><td>Daily Profit = Daily Revenue - kWh Cost * Miner Power (kW) * 24</td></tr>
<tr><td class="info-bullet">&#9632;</td><td>Daily Revenue tracks Daily Price, using Price Year N / Hash Factor</td></tr>
<tr><td class="info-bullet">&nbsp;</td><td>A Hash Factor of 1 gives perfect tracking</td></tr>
<tr><td class="info-bullet">&nbsp;</td><td>A Hash Factor of Price Year N / Price Today gives a constant Daily Revenue</td></tr>
</table>
<div>&nbsp;</div>
<?="$miners\n";?>
<div>&nbsp;</div>
<div>Latest from bitinfocharts.com, <?=date('Y-m-d');?>:</div>
<?php foreach ($coins as $c) {
    $p = number_format(${$c}[0], 2);
    $h = number_format(${$c}[1], 4);
    echo "<div>{${$c}[7]}, {${$c}[5]} $$p, $$h/{${$c}[6]}</div>\n";
}?>
</div>
                <form id="acww-formwrapper" class=".col-xs-12 .col-sm-4 no-pad" method="post">
                    <h2 id="acww-header"> Crypto Mining Profit Calculator <div class="info-button" style="padding-right:12px;" onclick="showinfo(1);">?</div></h2>
                    <div style=".text-align:center; margin:1px 6px -6px;">
<?php foreach ($coins as $c) {
    $on = $coin == $c? 'coin-on' : '';
    echo "<span id=\"bttn-$c\" class=\"coin-off $on\" onclick=\"showcoin('$c');\">$c</span>\n";
}?>
                    </div>
                    <div id="acww-form">
                        <div class="form-group"><div class="input-group">
                            <input class="form-control var-label" value="Price Today:" readonly>
                            <span class="acww-addon input-group-addon"><span class="glyphicon"><b>$</b></span></span>
                            <input type="number" class="form-control acww-userinput <?=$p0_at;?>" tabindex="1" onchange="submit();" name="p0" value="<?=$p0;?>" placeholder="">
                        </div></div>
                        <div class="form-group"><div class="input-group">
                            <input class="form-control var-label" value="*Price Year <?=$years;?>:" readonly style="cursor:pointer !important;" onclick="incyears();">
                            <span class="acww-addon input-group-addon"><span class="glyphicon"><b>$</b></span></span>
                            <input type="number" class="form-control acww-userinput" tabindex="2" onchange="submit();" name="p1" value="<?=$p1;?>" placeholder="">
                        </div></div>
                        <div class="form-group"><div class="input-group">
                            <input class="form-control var-label" value="Hash Factor:" readonly>
                            <span class="acww-addon input-group-addon"><span class="glyphicon"><b></b></span></span>
                            <input type="number" class="form-control acww-userinput" tabindex="3" onchange="submit();" name="hf" value="<?=$hf;?>" placeholder="">
                        </div></div>
                        <div style="margin:-4px;"><hr></hr></div>
                        <div class="form-group"><div class="input-group">
                            <input class="form-control var-label" value="$/Hash Today:" readonly>
                            <span class="acww-addon input-group-addon"><span class="glyphicon"><b>$</b></span></span>
                            <input type="number" class="form-control acww-userinput <?=$dph_at;?>" tabindex="4" onchange="submit();" name="dph" value="<?=$dph;?>" placeholder="">
                        </div></div>
                        <div class="form-group"><div class="input-group">
                            <input class="form-control var-label" value="Miner Hash:" readonly>
                            <span class="acww-addon input-group-addon"><span class="glyphicon"><b></b></span></span>
                            <input type="number" class="form-control acww-userinput" tabindex="5" onchange="submit();" name="mh" value="<?=$mh;?>" placeholder="">
                        </div></div>
                        <div class="form-group"><div class="input-group">
                            <input class="form-control var-label" value="Miner Power:" readonly>
                            <span class="acww-addon input-group-addon"><span class="glyphicon"><b>W</b></span></span>
                            <input type="number" class="form-control acww-userinput" tabindex="6" onchange="submit();" name="mp" value="<?=$mp;?>" placeholder="">
                        </div></div>
                        <div class="form-group"><div class="input-group">
                            <input class="form-control var-label" value="I = Miner Cost:" readonly>
                            <span class="acww-addon input-group-addon"><span class="glyphicon"><b>$</b></span></span>
                            <input type="number" class="form-control acww-userinput" tabindex="7" onchange="submit();" name="mc" value="<?=$mc;?>" placeholder="">
                        </div></div>
                        <div class="form-group"><div class="input-group">
                            <input class="form-control var-label" value="kWh Cost:" readonly>
                            <span class="acww-addon input-group-addon"><span class="glyphicon"><b>$</b></span></span>
                            <input type="number" class="form-control acww-userinput" tabindex="8" onchange="submit();" name="kwh" value="<?=$kwh;?>" placeholder="">
                        </div></div>
                        <div style="margin:-4px;"><hr></hr></div>
                        <div class="form-group">
                            <div class="input-group">
                            <input class="form-control var-label" value="Holding ROI:" readonly>
                            <span class="acww-addon input-group-addon"><span class="glyphicon"><b></b></span></span>
                            <input type="text" class="form-control acww-userinput roi-output" name="tr" value="<?=$tr;?>" placeholder="" readonly>
                        </div></div>
                        <div class="form-group">
                            <div class="input-group">
                            <input class="form-control var-label" value="Mining ROI:" readonly>
                            <span class="acww-addon input-group-addon"><span class="glyphicon"><b></b></span></span>
                            <input type="text" class="form-control acww-userinput roi-output" name="mr" value="<?=$mr;?>" placeholder="" readonly>
                        </div></div>
                        <div style="display:none;"><input type="hidden" id="disp" name="disp" value="<?=$disp;?>"></div>
                        <div style="display:none;"><input type="hidden" id="coin" name="coin" value="<?=$coin;?>"></div>
                        <div class="clearfix"></div>
                    </div>
                </form>
                <div class="highcharts-container" id="highcharts-3" style="position: relative; overflow: hidden; text-align: left; line-height: normal; z-index: 0; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); font-family: Helvetica, Arial, Verdana, sans-serif; font-size: 13px; font-weight: normal; color: rgb(136, 136, 136);">
<div style="width:95%; margin:auto;"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
    <canvas id="canvas" style="display: block; height: 143px; width: 287px;" width="287" height="143" class="chartjs-render-monitor"></canvas>
    <div style="padding:2px 6px; font-family:monospace; font-size:11px;">
<?php
if ($_GET['debug']) foreach ($pro as $d) echo number_format($d, 2), ', ';
if ($_GET['debug']) echo 'days: ', $xd+1, ', total: ', number_format($sum, 2);
?>
    </div>
</div>
<style>
    canvas {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }
</style>
<script src="js/chart.min.js"></script>
<script src="js/utils.js"></script>
<script>
    var lineChartData = {
        labels: [<?php for ($x = 0; $x <= $xd+1; $x++) echo $x, ', '; ?>],
        datasets: [{
            label: ' Left, Daily Price',
            borderColor: window.chartColors.blue,
            backgroundColor: window.chartColors.blue,
            fill: false,
            yAxisID: 'y-axis-1',
            showLine: false,
            pointRadius: 0.5,
            data: [<?php foreach ($pri as $p) echo round($p, 2), ', '; ?>],
        }, {
            label: 'Right, Daily Profit',
            borderColor: window.chartColors.orange,
            backgroundColor: window.chartColors.orange,
            fill: false,
            yAxisID: 'y-axis-2',
            showLine: false,
            pointRadius: 0.5,
            data: [<?php foreach ($pro as $p) echo round($p, 2), ', '; ?>],
        }],
    };
    window.onload = function() {
        var ctx = document.getElementById('canvas').getContext('2d');
        window.myLine = Chart.Line(ctx, {
            data: lineChartData,
            options: {
                responsive: true,
                hoverMode: 'index',
                stacked: false,
                title: {
                    display: false,
                },
                legend: {labels: {boxWidth: 5, usePointStyle: true}},
                scales: {
                    xAxes: [{}],
                    yAxes: [{
                        type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: 'left',
                        id: 'y-axis-1',
                    }, {
                        type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: 'right',
                        id: 'y-axis-2',
                        gridLines: {
                            drawOnChartArea: false, // only want the grid lines for one axis to show up
                        },
                    }],
                }
            }
        });
        document.getElementById('footer').style.display = 'block';
    };
</script>
                </div>
                <div id="footer" style="font-size:13px; padding:0 2px 10px; display:none;">
                    <div style="display:inline-block;">&copy; <a href="mailto:info@mobius.fund">Mobius Fund</a></div>
                    <div style="display:inline-block; float:right;">Info: <a href="<?=$info;?>" target="_blank">bitinfocharts.com</a></div>
                </div>
            </div>
        </div>
    </body>
</html>

<?php
ob_flush();
flush();
?>

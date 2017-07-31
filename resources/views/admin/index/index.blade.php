@extends('admin.layouts.base')
@section('styles')

@endsection
@section('scripts')
@endsection
@section('content')
    <!-- page heading start-->
    <div class="page-heading">
        <h3>
            Dashboard
        </h3>
        <ul class="breadcrumb">
            <li>
                <a href="#">Dashboard</a>
            </li>
            <li class="active"> My Dashboard </li>
        </ul>
        <div class="state-info">
            <section class="panel">
                <div class="panel-body">
                    <div class="summary">
                        <span>yearly expense</span>
                        <h3 class="red-txt">$ 45,600</h3>
                    </div>
                    <div id="income" class="chart-bar"><canvas width="82" height="35" style="display: inline-block; width: 82px; height: 35px; vertical-align: top;"></canvas></div>
                </div>
            </section>
            <section class="panel">
                <div class="panel-body">
                    <div class="summary">
                        <span>yearly  income</span>
                        <h3 class="green-txt">$ 45,600</h3>
                    </div>
                    <div id="expense" class="chart-bar"><canvas width="68" height="35" style="display: inline-block; width: 68px; height: 35px; vertical-align: top;"></canvas></div>
                </div>
            </section>
        </div>
    </div>
    <!-- page heading end-->

    <!--body wrapper start-->
    <div class="wrapper">
        <div class="row">
            <div class="col-md-6">
                <!--statistics start-->
                <div class="row state-overview">
                    <div class="col-md-6 col-xs-12 col-sm-6">
                        <div class="panel purple">
                            <div class="symbol">
                                <i class="fa fa-gavel"></i>
                            </div>
                            <div class="state-value">
                                <div class="value">230</div>
                                <div class="title">New Order</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-12 col-sm-6">
                        <div class="panel red">
                            <div class="symbol">
                                <i class="fa fa-tags"></i>
                            </div>
                            <div class="state-value">
                                <div class="value">3490</div>
                                <div class="title">Copy Sold</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row state-overview">
                    <div class="col-md-6 col-xs-12 col-sm-6">
                        <div class="panel blue">
                            <div class="symbol">
                                <i class="fa fa-money"></i>
                            </div>
                            <div class="state-value">
                                <div class="value">22014</div>
                                <div class="title"> Total Revenue</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-12 col-sm-6">
                        <div class="panel green">
                            <div class="symbol">
                                <i class="fa fa-eye"></i>
                            </div>
                            <div class="state-value">
                                <div class="value">390</div>
                                <div class="title"> Unique Visitors</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--statistics end-->
            </div>
            <div class="col-md-6">
                <!--more statistics box start-->
                <div class="panel deep-purple-box">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-7 col-sm-7 col-xs-7">
                                <div id="graph-donut" class="revenue-graph"><svg height="220" version="1.1" width="443" xmlns="http://www.w3.org/2000/svg" style="overflow: hidden; position: relative;"><desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.1.2</desc><defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><path fill="none" stroke="#4acacb" d="M221.5,176.66666666666669A66.66666666666667,66.66666666666667,0,0,0,263.07879551323236,57.88811835950217" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#4acacb" stroke="none" d="M221.5,179.66666666666669A69.66666666666667,69.66666666666667,0,0,0,264.9498413113278,55.54308368567977L280.7497836063561,35.7405686622906A95,95,0,0,1,221.5,205Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#6a8bc0" d="M263.07879551323236,57.88811835950217A66.66666666666667,66.66666666666667,0,0,0,159.45101113025743,85.6203481426209" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#6a8bc0" stroke="none" d="M264.9498413113278,55.54308368567977A69.66666666666667,69.66666666666667,0,0,0,156.65880663111903,84.52326380903884L133.08019086061685,75.25899610323478A95,95,0,0,1,280.7497836063561,35.7405686622906Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#5ab6df" d="M159.45101113025743,85.6203481426209A66.66666666666667,66.66666666666667,0,0,0,179.91301928453285,162.10534981569367" stroke-width="2" opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 1;"></path><path fill="#5ab6df" stroke="none" d="M156.65880663111903,84.52326380903884A69.66666666666667,69.66666666666667,0,0,0,178.0416051523368,164.45009055739988L159.11952892679926,188.1580247235405A100,100,0,0,1,128.42651669538617,73.43052221393134Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#fe8676" d="M179.91301928453285,162.10534981569367A66.66666666666667,66.66666666666667,0,0,0,221.47905604932066,176.66666337679857" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path><path fill="#fe8676" stroke="none" d="M178.0416051523368,164.45009055739988A69.66666666666667,69.66666666666667,0,0,0,221.4781135715401,179.6666632287545L221.47015487028193,204.99999531193794A95,95,0,0,1,162.2385524804593,184.25012348736345Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="221.5" y="100" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#ffffff" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-style: normal; font-variant: normal; font-weight: 800; font-stretch: normal; font-size: 15px; line-height: normal; font-family: Arial;" font-size="15px" font-weight="800" transform="matrix(1.137,0,0,1.137,-30.3386,-15.2041)" stroke-width="0.8795278475711893"><tspan dy="6" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Bounce Rate</tspan></text><text x="221.5" y="120" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#ffffff" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-style: normal; font-variant: normal; font-weight: normal; font-stretch: normal; font-size: 14px; line-height: normal; font-family: Arial;" font-size="14px" transform="matrix(1.3819,0,0,1.3819,-84.5947,-42.7778)" stroke-width="0.7236180904522613"><tspan dy="5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">approx. 10%</tspan></text></svg></div>

                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-5">
                                <ul class="bar-legend">
                                    <li><span class="blue"></span> Open rate</li>
                                    <li><span class="green"></span> Click rate</li>
                                    <li><span class="purple"></span> Share rate</li>
                                    <li><span class="red"></span> Unsubscribed rate</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!--more statistics box end-->
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row revenue-states">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <h4>Monthly revenue report</h4>
                                <div class="icheck">
                                    <div class="square-red single-row">
                                        <div class="checkbox ">
                                            <div class="icheckbox_square-red checked" style="position: relative;"><input type="checkbox" checked="" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                                            <label>Online</label>
                                        </div>
                                    </div>
                                    <div class="square-blue single-row">
                                        <div class="checkbox ">
                                            <div class="icheckbox_square-blue" style="position: relative;"><input type="checkbox" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                                            <label>Offline </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <ul class="revenue-nav">
                                    <li><a href="#">weekly</a></li>
                                    <li><a href="#">monthly</a></li>
                                    <li class="active"><a href="#">yearly</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="clearfix">
                                    <div id="main-chart-legend" class="pull-right"><table style="font-size:smaller;color:#545454"><tbody><tr><td class="legendColorBox"><div style="border:1px solid #000000;padding:1px"><div style="width:4px;height:0;border:5px solid rgb(90,188,223);overflow:hidden"></div></div></td><td class="legendLabel">New Visitors</td><td class="legendColorBox"><div style="border:1px solid #000000;padding:1px"><div style="width:4px;height:0;border:5px solid rgb(255,134,115);overflow:hidden"></div></div></td><td class="legendLabel">Unique Visitors</td></tr></tbody></table></div>
                                </div>

                                <div id="main-chart">
                                    <div id="main-chart-container" class="main-chart" style="padding: 0px; position: relative;">
                                        <canvas class="flot-base" width="548" height="300" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 548px; height: 300px;"></canvas><div class="flot-text" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; font-size: smaller; color: rgb(84, 84, 84);"><div class="flot-x-axis flot-x1-axis xAxis x1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;"><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 96px; top: 279px; left: 24px; text-align: center;">0</div><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 96px; top: 279px; left: 75px; text-align: center;">1</div><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 96px; top: 279px; left: 126px; text-align: center;">2</div><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 96px; top: 279px; left: 177px; text-align: center;">3</div><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 96px; top: 279px; left: 229px; text-align: center;">4</div><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 96px; top: 279px; left: 280px; text-align: center;">5</div><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 96px; top: 279px; left: 331px; text-align: center;">6</div><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 96px; top: 279px; left: 383px; text-align: center;">7</div><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 96px; top: 279px; left: 434px; text-align: center;">8</div><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 96px; top: 279px; left: 485px; text-align: center;">9</div><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 96px; top: 279px; left: 533px; text-align: center;">10</div></div><div class="flot-y-axis flot-y1-axis yAxis y1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;"><div class="flot-tick-label tickLabel" style="position: absolute; top: 264px; left: 15px; text-align: right;">0</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 231px; left: 1px; text-align: right;">100</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 198px; left: 1px; text-align: right;">200</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 165px; left: 1px; text-align: right;">300</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 132px; left: 1px; text-align: right;">400</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 99px; left: 1px; text-align: right;">500</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 66px; left: 1px; text-align: right;">600</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 33px; left: 1px; text-align: right;">700</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 0px; left: 1px; text-align: right;">800</div></div></div><canvas class="flot-overlay" width="548" height="300" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 548px; height: 300px;"></canvas></div>
                                </div>
                                <ul class="revenue-short-info">
                                    <li>
                                        <h1 class="red">15%</h1>
                                        <p>Server Load</p>
                                    </li>
                                    <li>
                                        <h1 class="purple">30%</h1>
                                        <p>Disk Space</p>
                                    </li>
                                    <li>
                                        <h1 class="green">84%</h1>
                                        <p>Transferred</p>
                                    </li>
                                    <li>
                                        <h1 class="blue">28%</h1>
                                        <p>Temperature</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel">
                    <header class="panel-heading">
                        goal progress
                        <span class="tools pull-right">
                                <a href="javascript:;" class="fa fa-chevron-down"></a>
                                <a href="javascript:;" class="fa fa-times"></a>
                             </span>
                    </header>
                    <div class="panel-body">
                        <ul class="goal-progress">
                            <li>
                                <div class="prog-avatar">
                                    <img src="images/photos/user1.png" alt="">
                                </div>
                                <div class="details">
                                    <div class="title">
                                        <a href="#">John Doe</a> - Project Lead
                                    </div>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 70%">
                                            <span class="">70%</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="prog-avatar">
                                    <img src="images/photos/user2.png" alt="">
                                </div>
                                <div class="details">
                                    <div class="title">
                                        <a href="#">Cameron Doe</a> - Sales
                                    </div>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 91%">
                                            <span class="">91%</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="prog-avatar">
                                    <img src="images/photos/user3.png" alt="">
                                </div>
                                <div class="details">
                                    <div class="title">
                                        <a href="#">Hoffman Doe</a> - Support
                                    </div>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                            <span class="">40%</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="prog-avatar">
                                    <img src="images/photos/user4.png" alt="">
                                </div>
                                <div class="details">
                                    <div class="title">
                                        <a href="#">Jane Doe</a> - Marketing
                                    </div>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                            <span class="">20%</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="prog-avatar">
                                    <img src="images/photos/user5.png" alt="">
                                </div>
                                <div class="details">
                                    <div class="title">
                                        <a href="#">Hoffman Doe</a> - Support
                                    </div>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                                            <span class="">45%</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="text-center"><a href="#">View all Goals</a></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

<!--body wrapper end-->
@endsection
@extends('layouts.admin')

@section('content')
    <div class="row dashboard-detail ">
        <div class="col-4">
            <div class=" detail-container1">
                <img class="ms-3 mt-3" src="../images/dash detail 1.svg" alt="" />
                <span class="detail-h1">Total Active Businesses</span>
                <div class="detail-h2">49</div>
                <div style="border-top: 1px solid #ededed">
                    <span class="detail-h3">Update: October 20, 2023</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class=" detail-container1">
                <img class="ms-3 mt-3" src="../images/dash detail 2.svg" alt="" />
                <span class="detail-h1">Total Customer Interactions</span>
                <div class="detail-h2">560</div>

                <div style="border-top: 1px solid #ededed">
                    <span class="detail-h3">Update: October 20, 2023</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class=" detail-container1">
                <img class="ms-3 mt-3" src="../images/dash detail 3.svg" alt="" />
                <span class="detail-h1">Total Revenue</span>
                <div class="detail-h2">10589</div>

                <div style="border-top: 1px solid #ededed">
                    <span class="detail-h3">Update: October 20, 2023</span>
                </div>
            </div>
        </div>
    </div>



    <!-- ############################ CHARTS ############################  -->

    <div class="chart-container col-12 mt-3">
        <div class="chart-box1 ">
            <div class="chart-box ">
                <div class="chart-btn-div">
                    <span class="chart-heading">Total Customers</span>
                    {{-- <button>This Month <img class="ms-2" src="../images/Vector 1755.svg"
                        alt=""></button> --}}
                </div>
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
        <div class="chart-box2">
            <div class="chart-box ">
                <div class="chart-btn-div">
                    <span class="chart-heading">Local Business Owners</span>
                    {{-- <button>This Month <img class="ms-2" src="../images/Vector 1755.svg"
                        alt=""></button> --}}
                </div>
                <canvas id="orderChart"></canvas>
            </div>
        </div>
    </div>
@endsection

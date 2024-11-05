@extends('layouts.owner')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Social Media Clicks</h2>

        <div class="row">
            <!-- Facebook Clicks -->
            <div class="col-md-4 mb-4">
                <div class="card text-center shadow-lg">
                    <div class="card-body">
                        <i class="fab fa-facebook-square fa-4x text-primary mb-3"></i>
                        <h5 class="card-title text-center">Facebook Clicks</h5>
                        <p class="card-text">
                            <strong>{{ $facebookClicks->click_count ?? 0 }}</strong> clicks
                        </p>
                    </div>
                </div>
            </div>

            <!-- TikTok Clicks -->
            <div class="col-md-4 mb-4">
                <div class="card text-center shadow-lg">
                    <div class="card-body">
                        <i class="fab fa-tiktok fa-4x mb-3"></i>
                        <h5 class="card-title text-center">TikTok Clicks</h5>
                        <p class="card-text">
                            <strong>{{ $tiktokClicks->click_count ?? 0 }}</strong> clicks
                        </p>
                    </div>
                </div>
            </div>

            <!-- Instagram Clicks -->
            <div class="col-md-4 mb-4">
                <div class="card text-center shadow-lg">
                    <div class="card-body">
                        <i class="fab fa-instagram fa-4x text-danger mb-3"></i>
                        <h5 class="card-title text-center">Instagram Clicks</h5>
                        <p class="card-text">
                            <strong>{{ $instagramClicks->click_count ?? 0 }}</strong> clicks
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

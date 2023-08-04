@extends('master.backend')
@section('title',__('backend.feedback'))
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="email mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="col-12">
                                        <div
                                            class="page-title-box d-sm-flex align-items-center justify-content-between">
                                            <h4 class="mb-sm-0">@lang('backend.feedback') : #{{ $feedback->id }}</h4>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-4">
                                        <img class="me-3 rounded-circle avatar-sm"
                                             src="{{asset('https://img.favpng.com/4/4/24/feedback-png-favpng-SngTtuMQ8095AX53AbZMdwc0g.jpg')}}"
                                             alt="Generic placeholder image">
                                        <div class="flex-1">
                                            <h5 class="font-size-16 my-1">{{ \App\Models\Advert::find($feedback->advert_id)->description->title ?? '-'}}</h5>
                                            <small> {{ date('d.m.Y H:i:s',strtotime($feedback->created_at))}}</small>
                                        </div>
                                    </div>
                                    <div>
                                        <h5>@lang('backend.category'):{{ $feedback->category_id }}</h5>
                                        <h5>@lang('backend.content'): {{ $feedback->feedback }}</h5>
                                    </div>
                                    <a href="mailto:{{ $feedback->email }}" class="btn btn-primary waves-effect mt-4"><i
                                            class="mdi mdi-reply"></i>@lang('backend.visit-advert')</a>
                                    <a href="{{ route('backend.deleteFeedback',$feedback->id) }}"
                                       class="btn btn-danger waves-effect mt-4"><i class="mdi mdi-trash-can"></i>@lang('backend.visit-advert')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

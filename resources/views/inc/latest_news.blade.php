@if($latest_news)
    <div>
        <marquee>
            <h1 class="flex-sm-fill h5 my-2">
                <i class="fa fa-volume-up text-city" ></i>
                {{ $latest_news->title }} :
                <span class="font-size-sm text-muted">{{ $latest_news->description }}</span>
            </h1>
        </marquee>
    </div>
@endif

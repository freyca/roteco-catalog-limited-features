<div class="relative w-full">
    <div class="swiper index-default-carousel swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide" data-swiper-autoplay>
                <div class="bg-white-100 flex items-center justify-center rounded-2xl">
                    <img src="https://roteco.es/wp-content/uploads/2018/05/roteco-grande-1.jpg" />
                </div>
            </div>

            <div class="swiper-slide">
                <div class="bg-white-100 flex items-center justify-center rounded-2xl">
                    <img src="https://roteco.es/wp-content/uploads/2018/05/roteco-grande-2.jpg" />
                </div>
            </div>

            <div class="swiper-slide">
                <div class="bg-white-100 flex items-center justify-center rounded-2xl">
                    <img src="https://roteco.es/wp-content/uploads/2018/05/roteco-grande-3.jpg" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-center gap-8 lg:justify-start">
            @svg('heroicon-c-chevron-left', 'bg-primary-300 text-primary-700 swiper-button-prev rounded-full')
            @svg('heroicon-c-chevron-right', 'bg-primary-300 text-primary-700 swiper-button-next rounded-full')
        </div>
    </div>

    @vite('resources/js/index.js')
</div>

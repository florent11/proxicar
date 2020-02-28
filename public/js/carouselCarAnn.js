class carouselCarAnn
{
    carouselInit()
    {
        $('.carousel').carousel({
            interval: 2000
        })
    }  
}
const carousel = new carouselCarAnn;
carousel.carouselInit()
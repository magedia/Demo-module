define([
    'jquery'
], function($){
    'use strict';
    return function (config){
        let currentTimeString = new Date(Date());
        let currentTimeMilliseconds = Date.parse(currentTimeString) + (currentTimeString.getTimezoneOffset() * 60 * 1000);
        let lastResetTime = config.timerSettings.last_reset_time;
        let resetTimeout = config.timerSettings.reset_timeout;
        const nextResetTime = Date.parse(lastResetTime) + resetTimeout * 60 * 1000;
        let timeToReset = (nextResetTime - currentTimeMilliseconds) / 1000;
        const bodyTag = $('body');

        bodyTag.removeClass('active-reload');

        $('#reset_timeout').text(resetTimeout);
        let timer = setInterval(function tick (){
            if(timeToReset !== 0 && timeToReset > 10){
                timeToReset = timeToReset - 1;
                let minuteToReset = Math.floor(timeToReset / 60);
                let secondToReset = timeToReset % 60;
                $('#reset_timer').text(`${minuteToReset < 10 ? '0' + minuteToReset : minuteToReset}:${secondToReset < 10 ? '0' + secondToReset : secondToReset}`);
            }else{
                clearInterval(timer);
                bodyTag.addClass('active-reload')
                setTimeout(function (){
                    document.location.reload();
                }, 30000)
            }
        }, 1000);
    }
});

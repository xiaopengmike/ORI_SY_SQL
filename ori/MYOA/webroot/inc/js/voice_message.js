(function($){
var voiceMsg = {
  "init": function(){
    $(".vmPanel[action-type='play']").live("click", function(){
      $(this).toggle(
        function(){voiceMsg.play($(this));},
        function(){voiceMsg.stop();}
      ).trigger('click');
    });
   
    $(".vmPanel[action-type='download']").live("click", function(){voiceMsg.download($(this));});
  },
  "play": function(handler){
    var voice_data = handler.attr("action-data");
    this.stop();
    handler.find("[un='voiceStatus']").attr("class", "icoVoicePlaying");
    if(typeof(window.external.PlayVoiceMsg) != "undefined"){
      window.external.PlayVoiceMsg(voice_data);
    }else{
      StopVoiceMsg();  
    }
  },
  "stop": function(){
    if(typeof(window.external.PlayVoiceMsg) != "undefined"){
      window.external.StopVoiceMsg("");
    }
    StopVoiceMsg();
  },
  "download": function(handler){
    var voice_data = handler.attr("action-data-url");
    window.open(voice_data);
  }
}

$(function(){voiceMsg.init()});

})(jQuery);

function StopVoiceMsg(){
  jQuery("[un='voiceStatus']").attr("class", "icoVoice"); 
}

top.StopVoiceMsg = StopVoiceMsg;
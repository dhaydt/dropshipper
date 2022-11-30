<style>
    #floating{
        position: fixed;
        z-index: 21;
        width: auto;
        background-color: transparent;
        cursor: move;
        border-radius: 50%;
        justify-content: center;
        /* padding: 10px */
        align-items: center;
    }
    .chatus {
        z-index: 25;
        position: absolute;
    }
    .float-img {
        width: 150px;
        height: auto;
    }
    @media(min-width: 600px){
        #floating {
            display: none;
        }
    }
</style>
@php($floating=\App\Model\Banner::where('banner_type','Floating Banner')->where('published',1)->orderBy('id','desc')->first())
@if (isset($floating))
<div id="floating" style="position: fixed; right: 0px; bottom: 65px; width: 80px;height: 80px;">
    @php($url = $floating['url'])
    <input type="hidden" value="{{ $url }}" id="url">
    <a href="javascript:" class="chatus" id="test">
        <img class="float-img" src="{{asset('storage/app/public/banner')}}/{{$floating['photo']}}" alt="floating">
    </a>
</div>
@endif

@push('script')
    <script>
         $(function() {
      var tapCount=0;
      var doubleTapCount=0;
      var longTapCount=0;
      var swipeCount=0;
      var blackCount=0;
      //Enable swiping...
      $("#test").swipe( {
        tap:function(event, target) {
            var url = $('#url').val()
            window.open(url)
        //   msg(target);
        console.log('work',tapCount);
        },
        doubleTap:function(event, target) {
          doubleTapCount++;
          msg(target);
          return true;
        },
        longTap:function(event, target) {
          longTapCount++;
          msg(target);
        },
        swipe:function() {
          swipeCount++;
          $("#textText").html("You swiped " + swipeCount + " times");
        },
        excludedElements:"",
        threshold:50
      });
    //   $("#test_btn").click(function() {
    //     window.open("http://www.google.com");
    //   });
      //Assign a click handler to a child of the touchSwipe object
      //This will require the jquery.ui.ipad.js to be picked up correctly.
    //   $("#another_div").click( function(){
    //     blackCount++;
    //     $("#another_div").html("<h3 id='div text'>jQuery click handler fired on the black div : you clicked the black div "+
    //     blackCount + " times</h3>");
    //   });
    //   function msg(target) {
    //       $("#textText").html("You tapped " + tapCount +", double tapped " +  doubleTapCount + " and long tapped " +  longTapCount + " times on " +  $(target).attr("id"));
    //   }
    });

        // $(".chatus").on("tap",function(){
        //     var url = $('#url').val()
        //     window.open(url)
        // });
        $('#floating').draggable({
            scroll: false,
            containment: "#bg-container",

            start: function( event, ui ) {
                console.log("start top is :" + ui.position.top)
                console.log("start left is :" + ui.position.left)
            },
            drag: function(event, ui) {
                console.log('draging.....');
            },
            stop: function( event, ui ) {
                console.log("stop top is :" + ui.position.top)
                console.log("stop left is :" + ui.position.left)

            }
        });
    </script>
    {{-- <script type="text/javascript" src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script> --}}
@endpush

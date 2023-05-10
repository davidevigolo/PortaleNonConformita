var precedentDisplayStyle = "block";
$(document).ready(() => {
    let currPage = 0;
	precedentDisplayStyle = $(`div#0.tab`).eq(0).css('display');
    $('div.tab').each(function() {
        $(this).hide(0);
    });

    $('select.selector').change(function() {
        if($(this).find(':selected').text() == "Elimina"){
            $(this).remove();
        } 
    });

    $('div#0.tab').show(0);
    $('select.selector#first').change(function(e) {
        const tag = $(this).attr('tag');
        console.log(tag);
        $(`select.selector[tag="${tag}"]#first option:checked`).each(function() {
                var elem = $(this).parent();
                var clone = elem.clone();
                console.log();
                elem.after(clone);
                $(clone).removeAttr('tag','');
                $(clone).removeAttr("id","");
                $(clone).attr("name",tag);
                $(clone).val($(elem).val());
                $(clone).append($('<option>', {
                    value: null,
                    text: 'Elimina'
                }));
                $(clone).change(() => {
                    if($(clone).find(':selected').text() == "Elimina"){
                        $(clone).remove();
                    } 
                })
            });
    });

    /*$('form').submit((e) => {
        e.preventDefault();
        $('div#1.tab').css("opacity",0);
        $('div#2.tab').css("display","block");
        $('div#2.tab').css("opacity",1);
        setTimeout(() => {
            $('div#1.tab').css('transition-duration','0s');
            $('div#1.tab').hide(400,"linear", () => {
                $('div#1.tab').css('transition-duration','0.3s');
            });
        },300);
    });*/

    $('ul#formnav li').click(function() {
        let n = parseInt($(this).attr('id').substring(1,2));
        if(n == currPage || !(n <= 4 && n>=0)){
            return;
        }
        changeToNPage(n);
    });

    $('ul#formnav li').eq(0).css("background-color","rgb(138, 219, 247)");
    $('ul#formnav li').eq(0).css("transform","translateY(5px)");
    $('ul#formnav li').eq(0).css("box-shadow","none");

    function changeToNPage(num){
        $('ul#formnav li').each(function() {
            $(this).css("background-color","deepskyblue");
            $(this).css("box-shadow","0px 5px 0px rgb(0, 133, 177)");
            $(this).css("transform","none");
        })

        $(`ul#formnav li#n${num}`).eq(0).css("background-color","rgb(138, 219, 247)");
        $(`ul#formnav li#n${num}`).eq(0).css("transform","translateY(5px)");
        $(`ul#formnav li#n${num}`).eq(0).css("box-shadow","none");

        $(`div.tab`).each(function() {
            $(this).css('opacity',0);
            $(this).hide(0)
        });
        $(`div#${num}.tab`).eq(0).css('display',precedentDisplayStyle);
        $(`div#${num}.tab`).eq(0).css('opacity',1);
        /*setTimeout(() => {
            $(`div.tab`).each(function() {
                $(this).css('transition-duration','0s');
            });
            $(`div.tab`).each(function() {
                $(this).hide(400,'linear', () => {
                    $(this).css('transition-duration','0.3s');
                })
            });
            $(`div#${num}.tab`).eq(0).css('display',precedentDisplayStyle);
            $(`div#${num}.tab`).eq(0).css('opacity',1);
        },300);*/
        currPage = num;
    }

})
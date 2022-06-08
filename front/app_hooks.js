jQuery(function(){
    let $ = jQuery;


    $(document).on('click', '.dgc-front-top_table__refresh_btn', async function(e){
        e.preventDefault();

        let main = $(this).parents('.dgc-front-top_table');
        let game = main.data('game');

        let res = await $.get(`/wp-json/dgc/v1/top_table?game=${game}`)

        let dd = $(res);

        main.html(dd.html());


        return false;
    })

})
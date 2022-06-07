function initGameDino($) {

    class GameDino extends DGameBase {
        constructor() {
            super();
            console.log('GameDino', this)
        }

        init() {
            $('#gamedino').click(()=>this.savetest())
        }

        async savetest() {
            console.log('savetest');

            // debugger;

            await this.save_result(Math.random() * 1000 + 99|0)
            // await this.save_result(1234)

        }

        async save_result(score){
            return await super.save_result(score, 5975);
        }
    }

    let game = new GameDino();
    game.init();

    if (navigator.userAgent.toLowerCase().indexOf('chrome') > -1) {
        new DuRunner('.interstitial-wrapper', null, game);
      } else {
        document.getElementById("main-frame-notchrome").style.display="";
      }

    //   <button class="btn btn-primary test-save">test save</button>
}
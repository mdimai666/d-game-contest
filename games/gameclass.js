class DGameBase {

    async wpajax(action, data) {
        let post_data = Object.assign({
            action: action,
            nonce_code: d_game_contest_varibles.nonce,
        }, data);

        return await jQuery.post(d_game_contest_varibles.url, post_data, 'json')
    }

    ff5h(s) {
        return s.toString().length
    }

    csi(s) {
        let csi = (s * s * s / this.ff5h(s) - 2222) | 0;
        return csi
    }

    async save_result(score, game) {
        let data = {
            score: score,
            cs: this.csi(score),
            g: game
        }

        let res = await this.wpajax('d_game_contest_saveresult', data);

        // let d = new Date() / 1000 / 86400 | 0;


        // console.warn('>res', { ...res, csi: this.csi(score) });
        console.warn('>res', res);

        // return res.
    }
}
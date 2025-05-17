<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOK</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <style>
            /* .favbutton{
                background-image: url('PRZYCISK.png');
                background-size: cover;      
                background-repeat: no-repeat; 
                background-position: center;  
                width:100px;
                height:50px;
            } */

            .favbutton {
                border:5px violet solid;
                color:red;
            }
        </style>
</head>

<body>

    <div class="container">
        <br>
        <div id="app" style="max-width:400px">
            <div style="display:flex;justify-content:flex-end">
                <button class="btn-secondary" v-if="!favourite" @click="setFavourite">⭐Ulubione</button>
                <button class="favbutton btn-warning" v-if="favourite" @click="unsetFavourite">⭐Ulubione</button>
            </div>
            <br>
            <div v-for="(elem,index) in fragments" v-show="index == current">
                <audio controls :id="'audioelem'+index">
                    <source :src="'/mp3/'+elem.filename" type="audio/mpeg">
                </audio>
                <p style="height:20px"><span v-if="napisy"> {{elem.tekst}}</span></p>
                <br>
                <p style="font-size:10px">Id:{{elem.id}}</p>
                <p v-if="favourite">Razem: {{fragments.length}}. Zostało: {{fragments.length - current}} </p>
            </div>
         
            <div v-if="fragments.length > 0 && !end">
                <button class="btn btn-success" style="width:100px" @click="next" id="nextbutton"><span v-if="!napisy && played">📜</span> <span v-if="napisy && played">➡️</span> <span v-if="!played">▶️&nbsp;</span>Dalej</button>
                <button class="btn btn-warning" style="margin-left:5px" @click="addFav" v-if="!fragments[current].fav">➕⭐</button>
                <button class="btn btn-danger" style="margin-left:5px" @click="addFav" v-else>➖⭐</button>
            </div>

            <div v-if="end">
               <b> KONIEC </b> &nbsp; <button @click="refresh" class="btn btn-dark">🔄 Przeładuj</button>
            </div>
        </div>

    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.2/axios.min.js"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script>

        Vue.createApp({
            data() {
                return {
                    favourite:false,
                    fragments: [],
                    current: 0,
                    napisy: false,
                    played: false,
                    counterset: 1,
                    end:false
                }
            },
            methods: {
                refresh(){
                    location.reload()
                },
                setFavourite(){
                    this.favourite = true;
                    localStorage.favourite = 'JO';
                    location.reload();
                },
                unsetFavourite(){
                    this.favourite = false;
                    localStorage.favourite = '';
                    location.reload();
                },
                next() {
                    if (this.current >= this.fragments.length - 1) {
                        this.end = true;
                        this.fragments[this.current].counter += 1;
                        this.update(this.fragments[this.current]);
                        return;
                    }

                    let audi = document.getElementById('audioelem' + this.current);
                    if (this.napisy) {
                        audi.pause();
                    }
                    if (!this.played) {
                        audi.play();
                        this.played = true;
                        return;
                    }

                    if (!this.napisy) {
                        this.napisy = true

                        this.update(this.fragments[this.current]);

                    } else {
                        this.current++;

                        audi = document.getElementById('audioelem' + this.current);
                        this.napisy = false;
                    }

                    if (!this.napisy) {
                        audi.play();
                    }

                 

                },
                update(elem) {
                    let counter = parseInt(elem.counter) + 1;
                    axios.get('/api/update.php?id=' + elem.id + '&counter=' + counter);
                },
                addFav(){
                    let elem = this.fragments[this.current];
                    elem.fav = 1;
                    axios.get('/api/fav.php?id=' + elem.id);
                },
                async getData() {

                    let self = this;
                    await axios.get('/api/fragments.php').then((res) => self.fragments = res.data);
                    document.querySelector('#nextbutton').focus()

                    if(localStorage.favourite === 'JO'){
                        this.favourite = true;
                        this.fragments = this.fragments.filter((el)=>el.fav);
                        return;
                    }

                    let filtered = self.fragments.filter((el) => el.counter < self.counterset)

                    if (filtered.length == 0) {
                        this.counterset += 1;
                        this.getData();
                    }

                    this.fragments = filtered;

                }
            },

            async mounted() {
                this.getData();

               


                // let audi = document.getElementById('audioelem');


            }

        }).mount("#app")


    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <br><br>
        <p>Siemano</p>

        <div id="app">
            <div v-for="(elem,index) in fragments" v-show="index == current">
            <audio controls :id="'audioelem'+index" >
                <source :src="'/mp3/'+elem.filename" type="audio/mpeg">
            </audio>
            <p><span v-if="napisy"> {{elem.tekst}}</span></p>
            {{elem.counter}}

            Id:{{elem.id}}
            </div>
            <br><br>
            <button class="btn btn-success" @click="next">Dalej</button>

            <br>
            {{current}}

           

            <!-- <p><b>{{elem.counter}}</b></p> -->


        </div>

    </div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.2/axios.min.js"></script>

    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script>

        Vue.createApp({
            data() {
                return {
                    fragments:[],
                    dane: [
                        { file: 'output.mp3', tekst: "Let's get it crunk upon Have fun upon up in this dancery" },
                        { file: 'output2.mp3', tekst: "We got ya'll open, now ya floatin So you gots to dance for me" },
                        { file: 'output3.mp3', tekst: "Don't need no hateration, holleration In this dancery" },
                        { file: 'output4.mp3', tekst: "So just dance for me Come on everybody get on up " },
                        { file: 'output5.mp3', tekst: "Mary J. is in the spot tonight And I'ma make you feel alright" },
                        { file: 'output6.mp3', tekst: "Come on baby just party with me Let loose and set your body free" },
                        { file: 'output7.mp3', tekst: "Leave your situations at the door So when you step inside, jump on the floor" },
                        { file: 'output8.mp3', tekst: "Before you get loose and start to lose your mind Cop you a drink, go 'head and rock your ice" },
                        { file: 'output9.mp3', tekst: "Cause we celebrating No More Drama in our life With a Dre track pumpin', everybody's jumpin'" },
                        { file: 'output10.mp3', tekst: "Go ahead and twist your back and get your body bumpin' I told you leave your situations at the door" },
                        { file: 'output11.mp3', tekst: "So grab somebody and get your ass on the dance floor" },
                        { file: 'output12.mp3', tekst: "Work real hard to make a dime" },
                        { file: 'output13.mp3', tekst: "Leave all that BS outside" },
                        { file: 'output14.mp3', tekst: "Let's have fun tonight, no fights" },
                        { file: 'output15.mp3', tekst: "Making you dance all night and I" },
                        { file: 'output16.mp3', tekst: "Doesn't matter if you're white or black " },
                    ],
                    current:0,
                    napisy:false,
                    played:false,
                    counterset:1
                }
            },
            methods:{
                next(){

                    let audi = document.getElementById('audioelem'+this.current);
                    if(this.napisy){
                        audi.pause();
                    }
                    if(!this.played){
                        audi.play();
                        this.played = true;
                        return;
                    }

                    if(!this.napisy){
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

                    if(this.current > this.dane.length - 1){
                        this.current = 0;
                    }

                },
                update(elem){
                    let counter = parseInt(elem.counter)+1;
                    axios.get('/api/update.php?id='+elem.id+'&counter='+counter);
                },
            },
          
            async mounted(){
                let self = this;
                await axios.get('/api/fragments.php').then((res)=>self.fragments = res.data);

                let filtered = self.fragments.filter((el)=>el.counter < this.counterset)

                if(filtered.length == 0){
                    this.counterset += 1;
                    filtered = self.fragments.filter((el)=>el.counter < self.counterset)
                }

                // let audi = document.getElementById('audioelem');
               

            }

        }).mount("#app")


    </script>
</body>

</html>
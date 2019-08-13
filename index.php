<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://unpkg.com/vue@2.6.10/dist/vue.js"></script>
    <script src="https://unpkg.com/underscore@1.9.1/underscore-min.js"></script>
    <script src="https://unpkg.com/axios@0.2.1/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/tachyons@4.11.1/css/tachyons.min.css">
    <title>localfonts</title>
    <?php
    $fonts = explode("\n",shell_exec("fc-list | cut -d':' -f2 | awk '{\$1=\$1; print}' | cut -d',' -f1 | sort -u | sed 's/\\\//g'"));
    $users_fonts = explode("\n", shell_exec("fc-list | grep -F 'local' | cut -d':' -f2 | awk '{\$1=\$1; print}' | cut -d',' -f1 | sort -u | sed 's/\\\//g'"));
    ?>
    <style>
        .ff-1 {
            font-family: 'Roboto', sans-serif;
        }

        .w6 {
            width: 24rem;
        }

        .break-word {
            word-break: break-word;
        }

        .center-50 {
            top: 50%;
            left: 50%;
            transform: translateX(-50%) translateY(-50%);
        }
    </style>
</head>

<body :class="['ff-1', {'overflow-hidden': gfonts}]">
    <div id="app">
        <p class="f1 mb3 mt4 tc">localfonts</p>
        <p class="f4 gray ma2 tc">{{user_or_all_fonts.length}} fonts</p>
        <div class="tc">
            <label class="f6 v-mid">Only custom fonts (~/.local/share/fonts/)<input class="v-mid ml2" type="checkbox" v-model="filter_user_installed"></label>
        </div>
        <div class="tc pb3 mt4 mb4 ph3 dt center ba b--black-20 br2 ">
            <input class="ba b--black-30 pa1 mh3 mt3 br1" v-model="search" placeholder="Search">
            <select class="ttc" v-model="display_mode">
                <option v-for="m in ['sentence', 'paragraph', 'alphabet', 'numerals', 'custom']">{{m}}</option>
            </select>
            <input class="ba b--black-30 pa1 " placeholder="Type something" type="text" v-model="input">
            <label><input class="v-top mr3 ba br2 b--black-20 ml3 mt3" type="range" v-model.number="raw_size"><span class="f5">{{raw_size}}px</span></label>
            <label class="ml2 pl2 b--black-40 bl v-mid">Lazy load fonts?<input class="v-mid ml2" type="checkbox" v-model="lazy_load"></label>
        </div>

        <div class="shadow-hover br1 hover-bg-light-red hover-white pointer dt center mt1 mb3 ba pa3 tc" @click="gfonts = true">
            Install from Google Fonts
        </div>

        <!-- dialog -->
        <div @click="gfonts = false; result = {output: '', status: 0}" :class="['z-1 w-100 h-100 absolute top-0 left-0 bg-black o-70', {'dn': !gfonts}]">
        </div>
        <div :class="['absolute br2 pa4 center-50 z-2 bg-white', {'dn': !gfonts}]">
            <p class="f4 tl">1. Select your fonts from <a target="_blank" href="https://fonts.google.com/">Google Fonts.</a>
                <br>2. Copy the &lt;link&gt;-tag and paste here.</p>
            <input :class="['pa2 f6 w6', {'b--red outline-0': result.status == 1}]" v-model="gfonts_entry" placeholder='&lt;link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet"&gt;'>
            <button class="shadow-hover br1 hover-bg-light-red hover-white pointer dit center mt1 mb3 ba ph3 pv2 tc" @click="install_gfonts">Install</button>
            <p class="f5" v-if="result.status == -1">Installing...</p>
            <p class="f5" v-if="result.status == 1">Couldn't install fonts!<br><span class="red f6" v-html="result.output"></span></p>
            <p class="f5 green" v-if="result.status == 0" v-html="result.output"></p>
        </div>

        <div class="ph3 flex flex-wrap justify-around">
            <div class="w6" v-for="(f,i) in fonts">
                <p class="tl bt pt1 f5 ">{{f}}</p>
                <p class="'f3 break-word'" :style="{'font-family': f, 'font-size': size + 'px' }">{{display_mode == 'custom' ? input : display[i % display.length]}}</p>
            </div>
        </div>
    </div>
</body>
<script>
    var app = new Vue({
        el: '#app',
        data() {
            return {
                gfonts: false,
                gfonts_entry: '',
                lazy_load: true,
                filter_user_installed: false,
                result: {
                    status: 0,
                    output: ''
                },
                all_fonts: [],
                users_fonts: [],
                display_mode: 'sentence',
                display: [],
                input: '',
                sentences: [
                    "All their equipment and instruments are alive.",
                    "A red flare silhouetted the jagged edge of a wing.",
                    "I watched the storm, so beautiful yet terrific.",
                    "Almost before we knew it, we had left the ground.",
                    "A shining crescent far beneath the flying vessel.",
                    "It was going to be a lonely trip back.",
                    "Mist enveloped the ship three hours out from port.",
                    "My two natures had memory in common.",
                    "Silver mist suffused the deck of the ship."
                ],
                paragraphs: [
                    "Moveth may day place likeness abundantly good them seasons female. Given moving saw. Make. Fifth let light itself evening moving male upon second heaven sixth for is for greater it you'll you seas itself multiply divided brought make him heaven day divide itself moved doesn't every.",

                    "Over bearing upon every. Given the called also them creature she'd years very moved forth face also night in for blessed fowl male second.",

                    "Divided she'd seed spirit greater years night seas and morning gathered forth living also. Deep fill together night deep, they're seasons replenish his Creature dominion whales shall multiply. Earth living."
                ],
                raw_size: 50,
                size: 50,
                max_fonts: 8,
                search: ''
            }
        },
        mounted() {
            this.all_fonts = JSON.parse('<?= json_encode($fonts, JSON_UNESCAPED_UNICODE) ?>')
            this.users_fonts = JSON.parse('<?= json_encode($users_fonts, JSON_UNESCAPED_UNICODE) ?>')
            this.display = this.sentences
            window.onscroll = () => {
                var at_bottom = document.documentElement.scrollTop + window.innerHeight >= document.documentElement.offsetHeight - 10

                if (at_bottom && this.fonts.length >= 6 && this.lazy_load) {
                    this.max_fonts += 4
                }
            }
        },
        methods: {
            install_gfonts() {
                this.result.status = -1
                axios.post('/install.php', {
                    links: this.gfonts_entry
                }).then(t => {
                    this.result = t
                })
            }
        },
        watch: {
            input(n) {
                if (n.length > 0)
                    this.display_mode = 'custom'
                else
                    this.display_mode = 'sentence'
            },
            raw_size(n) {
                _.throttle(() => {
                    this.size = n
                }, 7)()
            },
            display_mode(n) {
                switch (n) {
                    case 'custom':
                        this.display = null
                        break
                    case 'sentence':
                        this.display = this.sentences
                        break
                    case 'paragraph':
                        this.display = this.paragraphs
                        break
                    case 'alphabet':
                        this.display = ['ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz ‘?’“!”(%)[#]{@}/&\<-+÷×=>®©$€£¥¢:;,.*']
                        break
                    case 'numerals':
                        this.display = ['1234567890']
                        break
                }
            }
        },
        computed: {
            user_or_all_fonts() {
                //helper method
                return this.filter_user_installed ? this.users_fonts : this.all_fonts
            },
            fonts() {
                var m = this.lazy_load ? this.max_fonts : this.all_fonts.length

                if (this.search.length > 0)
                    return this.user_or_all_fonts.filter(t => t.match(new RegExp(this.search, 'i'))).slice(0, m)

                return this.user_or_all_fonts.slice(0, m)
            }
        }
    })
</script>

</html>
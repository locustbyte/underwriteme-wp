var game = new Phaser.Game(890, 580, Phaser.AUTO, 'frame', {
    preload: preload,
    create: create,
    update: update
});

// var game = new Phaser.Game(1013, 600, Phaser.AUTO, 'phaser-example', { preload: preload, create: create });

// function preload() {

//     game.load.image('1', 'assets/9.png');
//     game.load.image('2', 'assets/j.png');
//     game.load.image('3', 'assets/A.png');
//     game.load.image('4', 'assets/9.png');
//     game.load.image('5', 'assets/j.png');
    

//     game.load.image('6', 'assets/9.png');
//     game.load.image('7', 'assets/j.png');
//     game.load.image('8', 'assets/j.png');
//     game.load.image('9', 'assets/A.png');
//     game.load.image('10', 'assets/9.png');


//     game.load.image('11', 'assets/j.png');
//     game.load.image('12', 'assets/j.png');
//     game.load.image('13', 'assets/j.png');
//     game.load.image('14', 'assets/j.png');
//     game.load.image('15', 'assets/j.png');

// }

// function create() {

//     //  This simply creates an Image using the picture we loaded above and positions it at 100 x 100

//     //  The difference between an Image and a Sprite is that you cannot animate or add a physics body to an Image

//     var image = game.add.image(0, 0, '1');
//     var image = game.add.image(188, 0, '2');
//     var image = game.add.image(356, 0, '3');
//     var image = game.add.image(544, 0, '4');
//     var image = game.add.image(732, 0, '5');

//     var image = game.add.image(0, 188, '6');
//     var image = game.add.image(188, 188, '7');
//     var image = game.add.image(356, 188, '8');
//     var image = game.add.image(544, 188, '9');
//     var image = game.add.image(732, 188, '10');
    
//     var image = game.add.image(0, 376, '11');
//     var image = game.add.image(188, 376, '12');
//     var image = game.add.image(356, 376, '13');
//     var image = game.add.image(544, 376, '14');
//     var image = game.add.image(732, 376, '15');

// }

var assets = [
    'A',
    '9',
    'Dragon_StickyWild',
    'envelope',
    'j',
    'Fan_Symbol',
    'Panda_Scatter',
    'q',
    'k',
    '99'
];

var gamefile = {
    "gamefile": {
        "bonusactivated": "true",
        "Symbols": {
                "one": "0",
                "two": "0",
                "three": "5",
                "four": "4",
                "five": "11",
                "six": "12",
                "seven": "2",
                "eight": "9",
                "nine": "9",
                "ten": "7",
                "eleven": "2",
                "twelve": "0",
                "thirteen": "3",
                "fourteen": "3",
                "fifteen": "8"
        }
    }
}


var jolly = 'parrot';

var paylines = [
    [ 1, 1, 1, 1, 1 ],
    [ 0, 0, 0, 0, 0 ],
    [ 2, 2, 2, 2, 2 ],
    [ 0, 1, 2, 1, 0 ],
    [ 2, 1, 0, 1, 2 ],
    [ 0, 0, 1, 0, 0 ],
    [ 2, 2, 1, 2, 2 ],
    [ 1, 0, 0, 0, 1 ],
    [ 1, 2, 2, 2, 1 ],
    [ 1, 0, 1, 0, 1 ],
    [ 1, 2, 1, 2, 1 ],
    [ 0, 1, 0, 1, 0 ],
    [ 2, 1, 2, 1, 2 ],
    [ 1, 1, 0, 1, 1 ],
    [ 1, 1, 2, 1, 1 ],
    [ 0, 1, 1, 1, 0 ],
    [ 2, 1, 1, 1, 2 ],
    [ 0, 1, 2, 2, 2 ],
    [ 2, 1, 0, 0, 0 ],
    [ 0, 2, 0, 2, 0 ],
    [ 2, 0, 2, 0, 2 ],
    [ 1, 0, 2, 0, 1 ],
    [ 1, 2, 0, 2, 1 ],
    [ 0, 0, 1, 2, 2 ],
    [ 2, 2, 1, 0, 0 ]
];

var reels;
var graphics;
var creditsText;
var scoreText;
var reelStop;
var music;
var winning;

var credits = 1000;
var spinning = false;

// Symbol class

var Symbol = function(game, x, y, key, index) {
    Phaser.Sprite.call(this, game, x, y, key);
    this.scale.set(0.5);
    this.index = index;
    this.tweenY = this.y;
};

Symbol.prototype = Object.create(Phaser.Sprite.prototype);
Symbol.prototype.constructor = Symbol;

Symbol.prototype.update = function() {
    this.y = this.tweenY % 1500;
    
    var middle = 600;
    var range = 130;
    if (this.y < middle - range) this.alpha = 1.0 - (middle - range - this.y)*0.005;
    else if (this.y > middle + range) this.alpha = 1.0 - (this.y - middle - range)*0.005;
    else this.alpha = 1.0;
};

repeatCounter = 0;
Symbol.prototype.spin = function(rand) {
    
    var isFirst = this.index == 0;
    var isNext1 = this.index == 1;
    var isNext2 = this.index == 2;
    var isNext3 = this.index == 3;
    var isLast = this.index == 4;
    var sym = this

    this.tweenY = this.y;
    
    var target = this.tweenY + 500;
    var start = game.add.tween(this).to({tweenY: target}, 300, Phaser.Easing.Back.In, false, this.index*200);
    
    var offset = 1700 + (this.index * 1800);
    target += offset + (rand * 150);
    var mid = game.add.tween(this).to({tweenY: target}, offset/5, Phaser.Easing.Linear.InOut);

    target += 500;
    var end = game.add.tween(this).to({tweenY: target}, 300, Phaser.Easing.Back.Out);
    
    mid.onComplete.add(function() {

         



        console.log("First")
console.log(sym.index)
console.log(sym.y)

            if( sym.index == 0 &&  sym.y >451 && sym.y< 500){
sym.loadTexture('einstein', 0)
console.log("ck")
                console.log(sym.y + " " + sym.key)
            }
        //     if( sym.y >640 && sym.y< 604){
        //         console.log(sym.y + " " + sym.key)

                
        //         sym.visible = true;
        //         console.log(sym)
        //     }
        //     if( sym.y >790 && sym.y< 800){
        //         console.log(sym.y + " " + sym.key)

        //         //sym.loadTexture('einstein', 0)
        //         sym.visible = true;
        //         console.log(sym)
        //     }
            




        //     console.log("K == "+repeatCounter)
        //     if ( repeatCounter == 2  ) {
        //         console.log("GOTCHA")
        //         //console.log(ff[repeatCounter])
                
        //     }
        //    // console.log(finalArray)
        //     repeatCounter ++;


        //     if (isNext1) {
        //     console.log("Second")
                        
        // }
        // if (isNext2) {
        //     console.log("Third")
            
        // }
        // if (isNext3) {
        //     console.log("Forth")
            
        // }
        // if (isLast) {
        //     console.log("check")
        //     game.time.events.add(100, checkResults);
        // }





        
    });


    target += 500;
    var end = game.add.tween(this).to({tweenY: target}, 300, Phaser.Easing.Back.Out);
    
    var isLast = this.y == 600 && this.index == 4;
    end.onComplete.add(function() {
        reelStop.play();
        if (isLast) {
            game.time.events.add(100, checkResults);
        }
    });
    
    start.chain(mid, end);
    start.start();
};

// Reel class
var finalArray = []
var Reel = function(game, index) {
    Phaser.Group.call(this, game);
    
    // Fisher-Yates
    for (var i = assets.length - 1; i > 0; i--) {
        var j = game.rnd.integerInRange(0, i);
        var temp = assets[j];
        assets[j] = assets[i];
        assets[i] = temp;
    }
    
    for (var i = 0; i < assets.length; i++) {
        this.add(new Symbol(game, index*168, i*150, assets[i], index));
        // console.log(new Symbol(game, index*168, i*150, assets[i], index))
        finalArray.push(new Symbol(game, index*168, i*150, assets[i], index))
    }
};

Reel.prototype = Object.create(Phaser.Group.prototype);
Reel.prototype.constructor = Reel;

Reel.prototype.spin = function(rand) {
    this.forEach(function(symbol) {
        
        symbol.spin(rand);
    });
};

// Line Graphics class

var Line = function(game) {
    Phaser.Graphics.call(this, game);
    this.filters = [game.add.filter('Glow')];
    this.lines = [];
    this.drawing = false;
    this.perc = 0;
    this.all = false;
    this.index = 0;
    this.lastPos = [];
};

Line.prototype = Object.create(Phaser.Graphics.prototype);
Line.prototype.constructor = Line;

Line.prototype.update = function() {
    if (this.drawing && this.lines.length > 0) {
        if (this.all) {
            if (this.perc <= 1) {
                for (var i = 0; i < this.lines.length; i++) {
                    this.drawSingleLine(i);
                }
                this.perc += game.time.physicsElapsed*0.5;
            }
            else {
                this.all = false;
                this.perc = 0;
                this.clear();
            }
        }
        else {
            if (this.perc <= 1) {
                this.drawSingleLine(this.index % this.lines.length);
                this.perc += game.time.physicsElapsed*0.5;
            }
            else {
                this.index++;
                this.perc = 0;
                this.clear();
            }
        }
    }
};

Line.prototype.drawLines = function() {
    this.drawing = true;
    this.perc = 0;
    this.all = true;
    this.index = 0;
    this.lastPos = [];
    this.clear();
};

Line.prototype.stopDrawing = function() {
    this.drawing = false;
    this.clear();
};

Line.prototype.drawSingleLine = function(index) {
    if (this.perc == 0) this.lastPos[index] = {x: this.lines[index].x[0], y: this.lines[index].y[0]};
    
    this.moveTo(this.lastPos[index].x, this.lastPos[index].y);

    if (this.lines[index].included[Math.ceil(this.perc*6)]) this.lineStyle(6, 0xffffff);
    else this.lineStyle(2, 0xffffff);

    var x = Math.round(game.math.catmullRomInterpolation(this.lines[index].x, this.perc));
    var y = Math.round(game.math.catmullRomInterpolation(this.lines[index].y, this.perc));
    this.lineTo(x, y);
    
    this.lastPos[index].x = x;
    this.lastPos[index].y = y;
}

// Glow shader

Phaser.Filter.Glow = function (game) {
    Phaser.Filter.call(this, game);

    this.fragmentSrc = [
        "precision lowp float;",
        "varying vec2 vTextureCoord;",
        "varying vec4 vColor;",
        'uniform sampler2D uSampler;',

        'void main() {',
            'vec4 sum = vec4(0);',
            'vec2 texcoord = vTextureCoord;',
            'for(int xx = -4; xx <= 4; xx++) {',
                'for(int yy = -3; yy <= 3; yy++) {',
                    'float dist = sqrt(float(xx*xx) + float(yy*yy));',
                    'float factor = 0.0;',
                    'if (dist == 0.0) {',
                        'factor = 2.0;',
                    '} else {',
                        'factor = 2.0/abs(float(dist));',
                    '}',
                    'sum += texture2D(uSampler, texcoord + vec2(xx, yy) * 0.002) * factor;',
                '}',
            '}',
            'gl_FragColor = sum * 0.025 + texture2D(uSampler, texcoord);',
        '}'
    ];
};

Phaser.Filter.Glow.prototype = Object.create(Phaser.Filter.prototype);
Phaser.Filter.Glow.prototype.constructor = Phaser.Filter.Glow;

// Main game functions

function preload() {
    game.scale.scaleMode = Phaser.ScaleManager.SHOW_ALL;
    
    for (var i = 0; i < assets.length; i++) {
        game.load.image(assets[i], 'assets/' + assets[i] + '.png');
    }
    game.load.image('button', 'assets/button_up.png');
    game.load.bitmapFont('kenfuture', 'assets/kenfuture.png', 'assets/kenfuture.fnt');
    game.load.audio('reelstop', 'assets/reelstop.wav');
    game.load.audio('music', 'assets/chopin.ogg');
    game.load.audio('winning', 'assets/winning.wav');
    game.load.image('einstein', 'assets/9.png');
}

function create() {
    game.world.setBounds(0, 0, 800, 1500);
    game.camera.y = 380;
    
    reels = game.add.group();
    for (var i = 0; i < 5; i++) {
        reels.add(new Reel(game, i));
    }
    reels.x = 25;
    
    graphics = game.world.add(new Line(game));
    
    var button = game.add.button(690, 910, 'button', clickSpinButton);
    button.alpha = 0.7;
    var buttonText = game.add.bitmapText(765, 922, 'kenfuture', 'SPIN', 32);
    buttonText.alpha = 0.8;
    
    creditsText = game.add.bitmapText(25, 405, 'kenfuture', 'Balance: ' + credits, 32);
    scoreText = game.add.bitmapText(25, 925, 'kenfuture', 'ReelNRG: 0', 32);
    
    reelStop = game.add.audio('reelstop');
    music = game.add.audio('music');
    winning = game.add.audio('winning');
}

function update() {
    
}

function clickSpinButton() {
    if (spinning || credits < 100) return;
    
    spinning = true;
    
    music.play();
    updateCredits(-100);
    graphics.stopDrawing();
    scoreText.text = 'Winnings:';
    
    reels.forEach(function(reel) {
        var rand = game.rnd.integerInRange(0,9);
        
        reel.spin(rand);
    });
}

function checkResults() {
    var results = [];
    for (var i = 0; i < 3; i++)
        results[i] = [];
    
    graphics.lines = [];
    
    music.stop();
    var score = 0;
    reels.forEach(function(reel) {
        reel.forEach(function(symbol) {
            if (symbol.y == 450)
                results[0][symbol.index] = symbol;
            else if (symbol.y == 600)
                results[1][symbol.index] = symbol;
            else if (symbol.y == 750)
                results[2][symbol.index] = symbol;
        });
    });
    
    for (var i = 0; i < paylines.length; i++) {
        var symbol = results[paylines[i][0]][0].key;
        

        
        var j = 1;
        for (; j < paylines[i].length; j++) {
            var current = results[paylines[i][j]][j].key;
            
            if (symbol == jolly) {
                symbol = current;
            }
            else {
                if (current != symbol && current != jolly)
                    break;
            }
        }
        
        if (j >= 3) {
            var lineX = [];
            var lineY = [];
            var included = [];
            for (var k = 0; k < paylines[i].length; k++) {
                var sprite = results[paylines[i][k]][k];
                
                if (k == 0) {
                    lineX.push(sprite.x + sprite.width/2 + 25 - 100);
                    lineY.push(sprite.y + sprite.height/2);
                    included.push(true);
                }
                
                lineX.push(sprite.x + sprite.width/2 + 25);
                lineY.push(sprite.y + sprite.height/2);
                included.push(k < j);
                
                if (k == 4) {
                    lineX.push(sprite.x + sprite.width/2 + 25 + 100);
                    lineY.push(sprite.y + sprite.height/2);
                    included.push(k < j);
                }
            }
            graphics.lines.push({x: lineX, y: lineY, included: included});
            score += Math.pow(10, j);
        }

    }
    
    graphics.drawLines();
    scoreText.text = 'Score: ' + score;
    if (score > 0) updateCredits(score);
    spinning = false;
}

function updateCredits(amount) {
    if (amount > 0) winning.play();
    
    credits += amount;
    creditsText.text = 'Crediti: ' + credits;
    
    var updateText = game.add.bitmapText(40 + creditsText.width, 405, 'kenfuture', (amount<0?'':'+') + amount, 32);
    var updateTween = game.add.tween(updateText).to({alpha: 0, y: updateText.y - 10}, 1000, Phaser.Easing.Linear.InOut, true);
    updateTween.onComplete.add(function() {
        updateText.destroy();
    });
}
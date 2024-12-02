let kolory = [0,1,2,3]

let karty = [0,1,2,3,4,5,6,7,8,9,10,11,12]


function buduj_talie(z_ilu) {
    let talia = []
    let nr_talii = 0 
    let i = 0
    while(nr_talii<z_ilu) {
        kolory.forEach((kolor) => {
            karty.forEach((karta) => {
                talia[i] = {"kolor": kolor, "karta": karta}
                i++
            })
        })
        nr_talii++
    }
    console.log(talia)
}
console.log("istnieje")
function test(object)
{
    object = document.getElementById("on")
    if(object.checked)
    {
        var code = document.getElementById("panel")
        code.style = "display: none;"
        console.log("0")
    }
    else
    {
        var code = document.getElementById("panel")
        code.style = ""
        console.log("1")
    }
}
function change(name, chech){var tmp = ""
         tmp1=codee.value.split("|")
         if(chech == true)
         {
         for(i=0;i<tmp1["length"]-1;i++){
         tmp2=tmp1[i].split(";")
         if(tmp2[0]==name){
         tmp=tmp+tmp2[0]+";1|"
         }else
         {tmp=tmp+tmp1[i]+"|"
         }
         }
     }
     else
     {
         for(i=0;i<tmp1["length"]-1;i++){
             tmp2=tmp1[i].split(";")
             if(tmp2[0]==name){
             tmp=tmp+tmp2[0]+";0|"
             }else
             {tmp=tmp+tmp1[i]+"|"
             }
             }
     }
         codee.value=tmp}
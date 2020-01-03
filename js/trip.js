function trip_print(){
    var divToPrint=document.getElementById("printArea");
    newWin=window.open('');
    newWin.document.write(divToPrint.outerHTML);
    newWin.print();
    newWin.close();
}
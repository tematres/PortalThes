/* ---------------------------- */
/* BOTAO GERAR                  */
/* ---------------------------- */


function btngerar()
{
    with(document.getElementById('resultwrapper')){
    
        var resultstr = strtermocatalog(
            xtr('termoresposta'),
            xtr('qualificadorresposta'),
            xtr('qualificadorresposta2'),
            xtr('generoresposta'),
            document.getElementById('dataresposta').value.trim(),
            xtr('geograficoresposta'),
            xtr('geograficoresposta2')
        );
        
    if (resultstr.trim().length > 0) {
        insertBefore(document.querySelectorAll('#resultado')[document.querySelectorAll('#resultado').length-1].cloneNode(true),document.querySelectorAll('#resultado')[0]);
        with(document.querySelectorAll('#resultado')[0]){
            style.visibility='visible';
            style.display='table-row';
            childNodes[1].innerText = resultstr;
            /*var client = new ZeroClipboard(childNodes[3].childNodes[1]);
            client.on( "ready", function( event ) {
              client.on("copy", function( event ) {
                event.clipboardData.setData('text/plain', childNodes[1].innerText);
              });
            });
            */
        }
    }
    }
}

function strtermocatalog(t,q1,q2,gf,d,g1,g2)
{
    if (htr(t)) {
        msgseterr('');
        return (t + ctr(q1,'\$\$x') + ctr(q2,'\$\$x') + ctr(gf,'\$\$v') + ctr(d,'\$\$y') + ctr(g1,'\$\$z') + ctr(g2,'\$\$z') + '\$\$2larpcal');
    } else if (!htr(q1) && !htr(q2)) {
        if (htr(gf)) {
            msgseterr('');
            return (gf + ctr(d,'\$\$y') + ctr(g1,'\$\$z') + ctr(g2,'\$\$z') + '\$\$2larpcal');
        } else if (htr(g1)) {
            msgseterr('');
            return (g1 + ctr(g2,'\$\$z') + ctr(d,'\$\$y') + '\$\$2larpcal');
        } else if (htr(g2)) {
              msgseterr('');
              return (g2 + ctr(d,'\$\$y') + '\$\$2larpcal');
        } else {
            msgseterr('dados insuficientes.');
            return '';
        }
    } else {
        msgseterr('Qualificador exige Termo.');
        return '';
    }
}

function msgseterr(strmsgerr)
{
    if (htr(strmsgerr)) {
        document.getElementById('btngerid').style.backgroundColor='red';
        document.getElementById('msgerr').innerText = strmsgerr;
    } else {
        document.getElementById('btngerid').style.backgroundColor='transparent';
        document.getElementById('msgerr').innerText = '';
    }
}

function ctr(strx,cif)
{
    if (htr(strx)) {
        return (cif+strx.trim());
    }
    return ''
}

function htr(strx)
{
    if (strx.trim().length > 0) {
        return true;
    }
    return false;
}

function xtr(idprt)
{
    var vparte = document.querySelector('#' + idprt).value;
    var dparte = document.querySelector('#' + idprt).dataset['d' + idprt];

    return vparte.trim();
    
    /*if(vparte.trim()==dparte.trim()){
        return vparte.trim();
    }*/
    return '';
}


function toggle_visibility(id)
{
       var e = document.getElementById(id);
    if (e.style.display == 'block') {
        e.style.display = 'none';
    } else {
        e.style.display = 'block';
    }
}

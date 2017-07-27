// Fonction permettant d'ajouter un exercice dans le jour du programme
$(document).ready(function(){
    
    
    $('.clone_exercice').click(function(e){ // boutton ajout exercice
        var $this= $(this);
        
        if ($this.data('added') >= 10) {
            alert('pas plus de 10 ajouts');
            return;
        }
        
        $this.data('added', $this.data('added') + 1);
        let copie = $('.exercice_template').clone(); // le template à cloner
        copie.removeClass('exercice_template'); // on incremente la classe pour pas demultiplier les clones
        
        var nbExercice = $('.exercice', $this.closest('.exercices')).length + 1;
        $('input, select, textarea', copie).each(function(){
            $(this).attr('name', $(this).attr('name').replace('#EXERCICE#', nbExercice));
            $(this).attr('name', $(this).attr('name').replace('#JOUR#', $this.data('jour')));
        });
        
        copie.find('h2').text(copie.find('h2').text().replace('#EXERCICE#', nbExercice));
        console.log(copie);
        $this.before(copie); // on place le clone avant le bouton
        copie.show();
    });
});

// Fonction qui masque la div exercices du formulaire programme quand on change le statut du jour 'entrainement/repos'
$(document).ready(function(e){
    $('.jour_statut').change(function(){
        var $this = $(this);
        var $exercices = $this.closest('.sub-menu').find('.exercices');
        
        if ($this.val() == 'repos') {
            $exercices.hide();
        } else {
            $exercices.show();
        }
    });
    
    
});


//$(".content:contains('Salut')").html("Bonjour");
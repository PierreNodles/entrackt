$(function() {

  /* -------------
  NAVBAR
  ------------- */
  
  // AU CHARGEMENT DE LA PAGE
  var navbar = $("nav.navbar.navbar-expand-lg.navbar-light.bg-light");

  // SCROLLTOP : COMPTE LE NOMBRE DE PIXELS AU-DESSUS DE L'ÉLÉMENT
  if ( ( $(window).width() > 991 ) && ( $( window ).scrollTop() === 0 ) ) {
    navbar.css({ "margin": "3rem 3rem 0 3rem" });

  } else if ( ( $(window).width() > 991 ) && ( $( window ).scrollTop() !== 0 ) ) {
    navbar.css({ "margin": 0 });

  } else {
    navbar.css({ "margin": "3rem 3rem 0 3rem" });
  }

  // RECALCULER LA WIDTH DE LA FENÊTRE À CHAQUE REDIMENSIONNEMENT DU NAVIGATEUR
  $(window).resize(function () {
    if ( ( $(window).width() > 991 ) && ( $( window ).scrollTop() === 0 ) ) {
      navbar.css({ "margin": "3rem 3rem 0 3rem" });

    } else if ( ( $(window).width() > 991 ) && ( $( window ).scrollTop() !== 0 ) ) {
      navbar.css({ "margin": 0 });

    } else {
      navbar.css({ "margin": "3rem 3rem 0 3rem" });
    }
  });

  // AU SCROLL
  $(window).scroll(function () {
    if ( ( $(window).width() > 991 ) && ( $( window ).scrollTop() === 0 ) ) {
      navbar.css({ "margin": "3rem 3rem 0 3rem" });

    } else if ( ( $(window).width() > 991 ) && ( $( window ).scrollTop() !== 0 ) ) {
      navbar.css({ "margin": 0 });

    } else {
      navbar.css({ "margin": "3rem 3rem 0 3rem" });
    }
  });

  /* -------------
  MODAL
  ------------- */

  $('.form').find('input, textarea').on('keyup blur focus', function (e) {

    var $this = $(this),
    label = $this.prev('label');

    if (e.type === 'keyup') {
      if ($this.val() === '') {
        label.removeClass('active highlight');
      } else {
        label.addClass('active highlight');
      }
    } else if (e.type === 'blur') {
      if( $this.val() === '' ) {
        label.removeClass('active highlight');
      } else {
        label.removeClass('highlight');
      }
    } else if (e.type === 'focus') {

      if( $this.val() === '' ) {
        label.removeClass('highlight');
      }
      else if( $this.val() !== '' ) {
        label.addClass('highlight');
      }
    }

  });


  $('.tab a').on('click', function (e) {

    e.preventDefault();

    $(this).parent().addClass('active');
    $(this).parent().siblings().removeClass('active');

    target = $(this).attr('href');

    $('.tab-content > div').not(target).hide();


    $(target).fadeIn(600);
  });

  /* -------------
  MODAL - FONCTIONNEMENT AJAX
  ------------- */

  $("#login_form").submit(function(event){
    event.preventDefault(); // Annuler le submit
    var data = $(this).serialize(); // On récupère tous les champs du formulaire

    var url = $(this).attr("data-url");
    console.log(url);

    $.post({
      url: url,
      data: data,
    }).done(function(response){
      var successCondition = $(response).find('#response').html();
      console.log(successCondition);

      if ( successCondition == null){
        location.reload();
        var username =$('#username').val();
        console.log(username);
      }

      if (successCondition == 'Bad credentials.'){
        $("#ajax_error").html('Vos identifiants de connexion sont incorrects');
      }
    });
  });

  $("#register_form").submit(function(event){
    event.preventDefault(); // Annuler le submit
    var data = $(this).serialize(); // On récupère tous les champs du formulaire
    console.log(data);

    var url = $(this).attr("data-url");

    $.post({
      url: url,
      data: data,
      dataType: 'json'
    }).done(function(response){

      if (response.status == false ){
        $("#errorPassword").html(response.password);
        $("#errorUsername").html(response.username);
        $("#errorEmail").html(response.email);
        $("#alreadyTaken").html(response.alreadyTaken);
        $("#emailNotValid").html(response.emailNotValid);
        $("#passwordNotValid").html(response.passwordNotValid);
      }

      if (response.status == true ){
        console.log(response);
        $("#success").html(response.response);
      }
    });
  });

  /* -------------
  SMOOTH ANCRE
  ------------- */

  $('a[href="#section4"]').click(function(){
     var the_id = $(this).attr("href");

     $('html, body').animate({
        scrollTop:$(the_id).offset().top
     }, 'slow');
     return false;
  });

  /* -------------
  FILTRE
  ------------- */

  $('#mix-wrapper').mixItUp({
    load: {
      sort: 'letter:asc'
    },
    animation: {
      effects: 'fade',
      duration: 700
    },
    selectors: {
      target: '.mix-target',
      filter: '.filter-btn',
      sort: '.sort-btn'
    },
    callbacks: {
      onMixEnd: function(state){
        console.log(state);
      }
    }
  });

  /* -------------
  DATE PICKER
  ------------- */

  var monthNames = {
    0: "Janvier",
    1: "Fevrier",
    2: "Mars",
    3: "Avril",
    4: "Mai",
    5: "Juin",
    6: "Juillet",
    7: "Août",
    8: "Septembre",
    9: "Octobre",
    10: "Novembre",
    11: "Decembre"
  };

  function Cal() {
    this.date = {};
    this.markup = {};
    this.date.today = new Date();
    this.date.today = new Date(this.date.today.getUTCDate(),this.date.today.getUTCMonth(),this.date.today.getUTCFullYear());
    this.date.browse = new Date();
    this.markup.row = "row";
    this.markup.cell = "cell";
    this.markup.inactive = "g";
    this.markup.currentMonth = "mn";
    this.markup.slctd = "slctd";
    this.markup.today = "today";
    this.markup.dayArea = "dayArea";
    this.elementTag = 'calendar';
    this.targetInput = '#hbdsdf';
    this.init = false;
    this.buildDOM();
    this.selectDate(this.date.today.getDate(),this.date.today.getMonth(),this.date.today.getFullYear());
    this.constructDayArea();
    this.updateInput('Date de naissance','','');


    t = this;
    $(document).ready(function(){
      $(document).click(function(ms){
        e = $('.'+t.elementTag+' .view');
        eco = e.offset();
        if(ms.pageX<eco.left || ms.pageX>eco.left+e.width() || ms.pageY<eco.top || ms.pageY>eco.top+e.height()) {
          if(!t.init) t.hide(300);
        }
      });
      $('.'+t.elementTag).on('click','.next-month',function(){
        t.setMonthNext();
      });
      $('.'+t.elementTag).on('click','.prev-month',function(){
        t.setMonthPrev();
      });
      $('.'+t.elementTag).on('click','.next-year',function(){
        t.setYearNext();
      });
      $('.'+t.elementTag).on('click','.prev-year',function(){
        t.setYearPrev();
      });
      $('.'+t.elementTag).on('click','.jump-to-next-month',function(){
        t.setMonthNext();
      });
      $('.'+t.elementTag).on('click','.jump-to-previous-month',function(){
        t.setMonthPrev();
      });

      $('.'+t.elementTag).on('click','.'+t.markup.currentMonth,function(){
        d = t.selectDate(t.date.browse.getUTCFullYear(),t.date.browse.getUTCMonth(),$(this).html());
        t.hide(300);
      });

      $('.'+t.elementTag).on('click','.title',function(){
        t.date.browse = new Date(t.date.today.getTime());
        t.constructDayArea(false);
      });

      $(t.targetInput).focus(function(){
        t.show(100);
        $(this).blur();
      });

    });


  }
  Cal.prototype.wd = function(wd) {
    if(wd==0) return 7;
    return wd;
  };
  Cal.prototype.buildDOM = function() {
    html = "<div class='clear "+this.elementTag+"'>\n<div class='view'>\n<div class='head'>\n<div class='title'><span class='m'></span> <span class='y'></span></div>\n</div>\n";
    html += "<div class='row th'>\n<div class='C'>M</div>\n<div class='C'>T</div>\n<div class='C'>W</div>\n<div class='C'>T</div>\n<div class='C'>F</div>\n<div class='C'>S</div>\n<div class='C'>S</div>\n</div>\n<div class='"+this.markup.dayArea+"'>\n";
    html += "</div>\n\n<div class='row nav'>\n\n<i class='btn prev prev-year fa fa-fast-backward'></i>\n<i class='btn prev prev-month fa fa-play fa-flip-horizontal'></i>\n<i class='btn next next-month fa fa-play'></i>\n<i class='btn next next-year fa fa-fast-forward'></i>\n</div>\n</div>\n</div>\n";
    $(html).insertAfter(this.targetInput);
    $(this.targetInput).css('cursor','pointer');
    this.hide(0);
  };
  Cal.prototype.constructDayArea = function(flipDirection) {
    newViewContent = "";
    wd = this.wd(this.date.browse.getUTCDay());
    d = this.date.browse.getUTCDate();
    m = this.date.browse.getUTCMonth();
    y = this.date.browse.getUTCFullYear();

    monthBgnDate = new Date(y,m,1);
    monthBgn = monthBgnDate.getTime();
    monthEndDate = new Date(this.getMonthNext().getTime()-1000*60*60*24);
    monthEnd = monthEndDate.getTime();

    monthBgnWd = this.wd(monthBgnDate.getUTCDay());
    itrBgn = monthBgnDate.getTime()-(monthBgnWd-1)*1000*60*60*24;

    i = 1;
    n = 0;
    dayItr = itrBgn;
    newViewContent += "<div class='"+this.markup.row+"'>\n";
    while(n<42) {
      cls = new Array("C",this.markup.cell);
      if(dayItr<=monthBgn) cls.push(this.markup.inactive,"jump-to-previous-month");
      else if(dayItr>=monthEnd+1000*60*60*36) cls.push(this.markup.inactive,"jump-to-next-month");
      else cls.push(this.markup.currentMonth);
      if(dayItr==this.date.slctd.getTime()+1000*60*60*24) cls.push(this.markup.slctd);
      if(dayItr==this.date.today.getTime()+1000*60*60*24) cls.push(this.markup.today);

      date = new Date(dayItr);
      newViewContent += "<div class='"+cls.join(" ")+"'>"+date.getUTCDate()+"</div>\n";
      i += 1;
      if(i>7) {
        i = 1;
        newViewContent += "</div>\n<div class='"+this.markup.row+"'>\n";
      }
      n += 1;
      dayItr = dayItr+1000*60*60*24;
    }
    newViewContent += "</div>\n";


    this.changePage(newViewContent,flipDirection);
    $('.'+this.elementTag+' .title .m').html(monthNames[m]);
    $('.'+this.elementTag+' .title .y').html(y);
    return newViewContent;
  };
  Cal.prototype.changePage = function(newPageContent,flipDirection) {

    multiplier = -1;
    mark = "-";
    if(flipDirection) {
      multiplier = 1;
      mark = "+";
    }

    oldPage = $('.'+this.elementTag+' .'+this.markup.dayArea+' .mArea');
    newPage = $("<div class='mArea'></div>").css('left',(-1*multiplier*224)+'px').html(newPageContent);
    $('.'+this.elementTag+' .'+this.markup.dayArea).append(newPage);

    $('.mArea').stop(1,1).animate({
      left: mark+"=224px"
    },300,function(){
      oldPage.remove();
    });
  };
  Cal.prototype.selectDate = function(d,m,y) {
    this.date.slctd = new Date(y,m,d);
    this.updateInput(y,m,d);
    this.constructDayArea(false);
    return this.date.slctd;
  };
  Cal.prototype.updateInput = function(d,m,y) {
    if(m=='') m = '';
    else m = monthNames[m];
    $(this.targetInput).val(d+" "+m+" "+y);
  };
  Cal.prototype.getMonthNext = function() {
    m = this.date.browse.getUTCMonth();
    y = this.date.browse.getUTCFullYear();
    if(m+1>11) return new Date(y+1,0);
    else return new Date(y,m+1);
  };
  Cal.prototype.getMonthPrev = function() {
    m = this.date.browse.getUTCMonth();
    y = this.date.browse.getUTCFullYear();
    if(m-1<0) return new Date(y-1,11);
    else return new Date(y,m-1);
  };
  Cal.prototype.setMonthNext = function() {
    m = this.date.browse.getUTCMonth();
    y = this.date.browse.getUTCFullYear();
    if(m+1>11) {
      this.date.browse.setUTCFullYear(y+1);
      this.date.browse.setUTCMonth(0);
    } else {
      this.date.browse.setUTCMonth(m+1);
    }
    this.constructDayArea(false);
  };
  Cal.prototype.setMonthPrev = function() {
    m = this.date.browse.getUTCMonth();
    y = this.date.browse.getUTCFullYear();
    if(m-1<0) {
      this.date.browse.setUTCFullYear(y-1);
      this.date.browse.setUTCMonth(11);
    } else {
      this.date.browse.setUTCMonth(m-1);
    }
    this.constructDayArea(true);
  };
  Cal.prototype.setYearNext = function() {
    y = this.date.browse.getUTCFullYear();
    this.date.browse.setUTCFullYear(y+1);
    this.constructDayArea(false);
  };
  Cal.prototype.setYearPrev = function() {
    y = this.date.browse.getUTCFullYear();
    this.date.browse.setUTCFullYear(y-1);
    this.constructDayArea(true);
  };
  Cal.prototype.hide = function(duration) {
    $('.'+this.elementTag+' .view').slideUp(duration);
  };
  Cal.prototype.show = function(duration) {
    t = this;
    t.init = true;
    $('.'+this.elementTag+' .view').slideDown(duration,function(){
      t.init = false;
    });
  };

  var c = new Cal();
});

/*
 * jQuery Plugin: Tokenizing Autocomplete Text Entry
 * Version 1.6.0
 *
 * Copyright (c) 2009 James Smith (http://loopj.com)
 * Licensed jointly under the GPL and MIT licenses,
 * choose which one suits your project best!
 *
 */

function verificar(){
      $.ajax({type: "GET",url:"vef.php",data:"infacta="+document.formulario.infacta.value,success:function(msg){
         $("#final").html(msg);
      }})
   }

function verificardni(){
      $.ajax({type: "GET",url:"dnivef.php",data:"dnicompr="+document.formulario.dni.value,success:function(msg){
         $("#dnifinal").html(msg);
      }})
   }

function verificarpersonas(){
      $.ajax({type: "GET",url:"verifperson.php",data:"verperx="+document.formx.valordni.value,success:function(msg){
         $("#verper").html(msg);
      }})
   }

import '../css/datepicker.css'
import { Datepicker } from 'vanillajs-datepicker';

const elem = document.querySelector('.date-field');
const datepicker = new Datepicker(elem, {
  format: 'dd/mm/yyyy',
  minDate: '1900-01-01',
  orientation: 'bottom',
  startView: 2,
  title: 'Date de naissance'
}); 
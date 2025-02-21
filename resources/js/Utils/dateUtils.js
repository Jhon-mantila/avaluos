import { format } from 'date-fns'; //date-fns para darle formato a las fechas

  //Darle formato a las fechas
  export const formatDate = (dateString) => {
    return format(new Date(dateString), 'yyyy-MM-dd HH:mm:ss');
};
/**
 * Created by DESIGN on 27/06/2016.
 */
var chart = c3.generate({
    data: {
        x: 'x',
        columns: [
            ['x', '2016-01-01', '2016-01-02', '2016-01-03', '2016-01-04', '2016-01-05'],

            ['Dolar Paralelo', 3.4196, 3.4201, 3.4228, 3.4200, 3.4251],
            ['Dolar Turismo', 3.4203, 3.4208, 3.4233, 3.4230, 3.4258]
        ]
    },
    axis: {
        x: {
            type: 'timeseries',
            tick: {
                //format: '%d-%m-%Y'
                format: '%d'
            }
        }
    }
});
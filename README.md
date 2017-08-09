# WordSalad
WordSalad is a text analysis service designed to analyze the authenticity of private writing samples. WordSalad verifies that a writing sample was written by a student rather then fabricated using other means such as text to speech or random key strokes.

## Installation Instructions
- The WordSalad dashboard requires multible JS libraries to displaying informaion about the service. In the public folder you will find a bower.json fill that can be used to gather all the nessary JS libraries. 
- Paths in the `ResourceModel.php` will be to be changed to match paths in the bower_components directory. 

## Steup Instructions
1. Create a new database and run the `worldsalad_database_scheme.sql`
2. Configure `config.php` file located in `application/config`
    * If you get an error check your database connection informaion
2. Navigate to the Matrix page in the dashboard.
3. Click the `Initialize Matrix` button
4. Click the `Train Matrix` button
5. Next creatre a back up of the current matrix but clicking `Create Backup`

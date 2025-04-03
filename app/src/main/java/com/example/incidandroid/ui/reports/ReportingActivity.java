package com.example.incidandroid.ui.reports;

import static android.view.View.GONE;
import static android.view.View.VISIBLE;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.example.incidandroid.R;
import com.example.incidandroid.model.ReportCard;
import com.example.incidandroid.model.ReportSeverity;
import com.example.incidandroid.ui.connexion.MainActivity;
import com.example.incidandroid.utils.Api;
import com.example.incidandroid.utils.CustomListReservationAdapter;

import androidx.appcompat.app.AppCompatActivity;

import org.json.JSONException;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

public class ReportingActivity extends AppCompatActivity {

    /* Données de la réservation concernée */
    private final static String[] RESERVATION_KEYS
            = {"id","date"};

    private String reservationId;
    private String reservationDate;


    // Données si on modifie l'incident
    private String reportId;
    private String summaryReport;
    private String descReport;
    private int severityIndexReport;
    private boolean inModification;
    private boolean isItContacted;

    /* Données de l'activité */
    private TextView title;
    private EditText summary;
    private TextView summaryError;
    private EditText description;
    private Spinner reportSeveritySelector;
    private RadioButton contactIt;
    private Button sendReport;
    private TextView severityDesc;

    private RadioGroup itRGroup;
    private ReportSeverity selectedSeverity;
    private Button goBack;

    private String apiKey;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.reporting_page);
        Intent intentParent = this.getIntent();
        reservationId = String.valueOf(intentParent.getIntExtra(RESERVATION_KEYS[0], -1));
        reservationDate = intentParent.getStringExtra(RESERVATION_KEYS[1]);

        // Données si on modifie un incident
        reportId = intentParent.getIntExtra(ReportCard.REPORT_KEYS[0], -1)+"";
        summaryReport = intentParent.getStringExtra(ReportCard.REPORT_KEYS[1]);
        descReport = intentParent.getStringExtra(ReportCard.REPORT_KEYS[2]);
        severityIndexReport = intentParent.getIntExtra(ReportCard.REPORT_KEYS[3], -1);
        isItContacted = intentParent.getBooleanExtra(ReportCard.REPORT_KEYS[4], false);
        inModification = intentParent.getBooleanExtra(ReportCard.REPORT_KEYS[6], false);

        if (reservationDate == null) { // Si on modifie un incident, pas qu'on en crée un
            reservationDate = intentParent.getStringExtra(ReportCard.REPORT_KEYS[5]);
        }


        String apiKey = intentParent.getStringExtra(MainActivity.CLE_API);

        title = findViewById(R.id.incident_title);
        summary = findViewById(R.id.incident_summary);
        summaryError = findViewById(R.id.summary_not_filled);
        description = findViewById(R.id.incident_desc);
        reportSeveritySelector = findViewById(R.id.spinner_report_severity);
        severityDesc = findViewById(R.id.severity_desc);
        contactIt = findViewById(R.id.contact_it_yes);
        itRGroup = findViewById(R.id.radiogroup_contact_it);
        sendReport = findViewById(R.id.send_report);
        goBack = findViewById(R.id.back_to_resa_list);

        summaryError.setVisibility(GONE); // L'erreur n'est pas encore provoquée

        Log.i("Hi there !!!", isItContacted+"");
        if (isItContacted) { // Radio 'oui' sélectionné si la modif le demande
            itRGroup.check(R.id.contact_it_yes);
        }

        reservationDate
                = CustomListReservationAdapter.getFrenchDateFormat(reservationDate);

        title.setText(getString(R.string.incident_title, reservationDate));

        ArrayList<ReportSeverity> severityValues;

        /*
         * On empêche la liste de contenir la valeur NEUTRAL
         * car cette valeur est utile pour désigner les réservations
         * sans signalements. Cette valeur ne peut pas désigner
         * un type de sévérité pour les incidents
         */

        severityValues = new ArrayList<>();
        for (ReportSeverity reportType: ReportSeverity.values()) {
            if (reportType.getSeverityIndex() != 5) {
                severityValues.add(reportType);
            }
        }

        ArrayAdapter<ReportSeverity> severitySpinnerAdapter = new ArrayAdapter<>(
                this,
                android.R.layout.simple_spinner_dropdown_item,
                severityValues
        );


        reportSeveritySelector.setAdapter(severitySpinnerAdapter);

        reportSeveritySelector.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                selectedSeverity = (ReportSeverity) parent.getSelectedItem();
                severityDesc.setText(
                        getString(R.string.desc_report_severity,
                                  selectedSeverity.getDesc()));
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
            }
        });

        itRGroup.setOnCheckedChangeListener((elt, index) -> {
            isItContacted = contactIt.isChecked(); // Si radio 'oui' est actif, true
        });

        // Si on modifie un incident, on affiche les données :

        if (summaryReport != null) {
            summary.setText(summaryReport);
        }

        if (descReport != null) {
            description.setText(descReport);
        }

        if (severityIndexReport != -1) {
            for (int index = 0; index < severitySpinnerAdapter.getCount(); index++) {
                if (severitySpinnerAdapter.getItem(index)
                                          .getSeverityIndex() == severityIndexReport) {
                    reportSeveritySelector.setSelection(index);
                }
            }
        }

        if (inModification) {

            sendReport.setOnClickListener((view) -> {

                if (summary.getText().toString().trim() .length() > 0) {
                    summaryError.setVisibility(GONE); // L'erreur a été corrigé

                    Api.getInstance().modifyReport(this, apiKey, reportId,
                        summary.getText().toString(), description.getText().toString(),
                        selectedSeverity.getSeverityIndex(), isItContacted,
                        msg -> {
                            try {
                                Log.i("Incidandroid - incident", msg.getString("message"));
                            } catch (JSONException e) {
                                //DO NOTHING
                            }

                            Toast.makeText(this, getString(R.string.report_modification_success_msg),
                                    Toast.LENGTH_LONG).show();
                            finish();

                        },  error -> Log.e("Incidandroid - incident", "Impossible d'enregistrer l'incident"));
                } else {
                    /* On informe l'utilisateur qu'il doit saisir
                     * une description de 1 à 50 caractères
                     */
                    summaryError.setVisibility(VISIBLE);
                }
            });
        } else { // On crée un rapport d'incident
            sendReport.setOnClickListener((view) -> {
                if (summary.getText().toString().length() > 0) {
                    summaryError.setVisibility(GONE); // L'erreur a été corrigé

                    Api.getInstance().sendReport(this, apiKey, reservationId,
                        summary.getText().toString(), description.getText().toString(),
                        selectedSeverity.getSeverityIndex(), isItContacted,
                        msg -> {
                            try {
                                Log.i("Incidandroid - incident", msg.getString("message"));
                            } catch (JSONException e) {
                                //DO NOTHING
                            }

                            Toast.makeText(this, getString(R.string.report_confirmation_msg),
                                    Toast.LENGTH_LONG).show();
                            finish();

                        },  error -> Log.e("Incidandroid - incident", "Impossible d'enregistrer l'incident"));
                } else {
                    /* On informe l'utilisateur qu'il doit saisir
                     * une description de 1 à 50 caractères
                     */
                    summaryError.setVisibility(VISIBLE);
                }
            });
        }
        // On retourne sur la page précédent l'ouverture de la page actuelle
        goBack.setOnClickListener((view) -> finish());
    }
}

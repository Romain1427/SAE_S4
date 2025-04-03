package com.example.incidandroid.ui.reservations;

import static android.view.View.GONE;
import static android.view.View.VISIBLE;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.example.incidandroid.R;
import com.example.incidandroid.data.VolleyCallback;
import com.example.incidandroid.model.Report;
import com.example.incidandroid.model.Reservation;
import com.example.incidandroid.model.ReservationCard;
import com.example.incidandroid.ui.connexion.MainActivity;
import com.example.incidandroid.utils.Api;
import com.example.incidandroid.utils.CustomListReservationAdapter;
import com.google.gson.Gson;
import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonParser;

import java.util.ArrayList;
import java.util.List;

/** 11:07
 * Contrôleur de l'affichage des différentes réservations faîtes par l'employé
 * étant connecté. Les différentes données affichées sont celles associées à
 * la clé de l'API qui est unique pour chaque compte.
 */
public class ReservationsActivity extends AppCompatActivity {

    ListView reservationsUI;
    Intent reservationDetails;
    RelativeLayout spinningLoader;
    RelativeLayout loadingMessageContainer;
    TextView loadingMessage;
    private String API_KEY;
    public static final String[] DETAILS_KEY = {"reservant", "details.date",
            "details.creneau", "details.room", "details.activity", "details.idResa"};

    @Override
    protected void onResume() {
        super.onResume();
        loadReservationsPage();
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        loadReservationsPage();
    }

    /**
     * Généralise le moment où on arrive sur la page, et où on revient
     * sur la page des réservations
     */
    private void loadReservationsPage() {

        setContentView(R.layout.reservations_activity);
        Api api = Api.getInstance();

        spinningLoader = findViewById(R.id.loadingPanel);
        loadingMessageContainer = findViewById(R.id.loadingTextView);
        loadingMessage = findViewById(R.id.loadingText);
        reservationsUI = (ListView) findViewById(R.id.listView);
        reservationsUI.setVisibility(GONE);

        loadingMessageContainer.setVisibility(VISIBLE);
        spinningLoader.setVisibility(VISIBLE);
        loadingMessage.setText("Chargement des réservations ...");

        reservationDetails
                = new Intent(ReservationsActivity.this, DetailedReservationActivity.class);

        Intent receivedIntent = getIntent();
        API_KEY = receivedIntent.getStringExtra(MainActivity.CLE_API);

        api.reservations(getApplicationContext(), API_KEY, jsonArray -> {
            Gson gson = new Gson();
            // result = JSONArray <=> Conversion en donnée exploitable
            JsonArray jsonResult = gson.fromJson(jsonArray.toString(), JsonArray.class);
            List<ReservationCard> list = new ArrayList<>();
            for (JsonElement reservationData : jsonResult) {
                // Convertit chaque objet JSON en instance de Reservation
                list.add(new ReservationCard(gson.fromJson(reservationData, Reservation.class),
                        ReservationsActivity.this, API_KEY));
            }
            loadingMessage.setText("Chargement des incidents ...");

            createReservations(list);
        }, error -> Log.e("Incidandroid", "Impossible de récupérer la liste des réservations"));
    }

    private void createReservations(List<ReservationCard> reservationsData) {

        loadingMessage.setText("Affichage des réservations ...");
        showReservations(reservationsData);
    }

    private void showReservations(List<ReservationCard> reservationsData) {
        spinningLoader.setVisibility(GONE);
        loadingMessageContainer.setVisibility(GONE);
        reservationsUI.setVisibility(VISIBLE);

        if (reservationsData.size() > 0) {

            reservationsUI.setAdapter(new CustomListReservationAdapter(ReservationsActivity.this
                    ,this, reservationsData));

            // Ajout d'un écouteur de clic
            reservationsUI.setOnItemClickListener(new AdapterView.OnItemClickListener() {
                @Override
                public void onItemClick(AdapterView<?> parent, View view, int position, long id) {

                    ReservationCard currentReservation = reservationsData.get(position);
                    Intent intention = new Intent(getApplicationContext(),
                                                  DetailedReservationActivity.class);
                    intention.putExtra(MainActivity.CLE_API, API_KEY);
                    intention.putExtra(DETAILS_KEY[0],
                            getIntent().getStringExtra(MainActivity.USERNAME));
                    intention.putExtra(DETAILS_KEY[1],
                            currentReservation.getReservationData().getReservationDate());
                    intention.putExtra(DETAILS_KEY[2],
                            currentReservation.getReservationData().getStartHour()
                            + " - "
                            + currentReservation.getReservationData().getEndHour());
                    intention.putExtra(DETAILS_KEY[3],
                            currentReservation.getReservationData().getReservationRoom());
                    intention.putExtra(DETAILS_KEY[4],
                            currentReservation.getReservationData().getReservationActivity());
                    intention.putExtra(DETAILS_KEY[5],
                            currentReservation.getReservationData().getIdReservation());
                    startActivity(intention);
                }
            });

        } else {
            TextView errorPlaceholder = findViewById(R.id.msg_erreur);
            errorPlaceholder.setText(getString(R.string.no_reservation_msg));
        }
    }
}
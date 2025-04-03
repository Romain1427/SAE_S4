package com.example.incidandroid.utils;

import android.content.Context;
import android.util.Log;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.example.incidandroid.data.HttpsTrustManager;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;
import java.util.function.Consumer;

public class Api {
    // protected final static String API_URL = "http://192.168.56.10/api/src/";
    protected final static String API_URL = "http://10.0.2.2/StatiSalle_SAE-S3_WEB-main/sae-s4-a-2024-2025-sw1-3/src/";

    private static Api api;

    private RequestQueue fileRequete;

    private Api() {
        // On évite les problèmes de certificat SSL
        HttpsTrustManager.allowAllSSL();
    }

    public static Api getInstance() {
        if (api == null) {
            api = new Api();
        }
        return api;
    }

    /**
     * Crée une connexion avec l'API gérant les réservations de
     * l'application web. La connexion est uniquement faite par
     * l'utilisation d'un compte employé de ladite application web.
     * @param context le contexte de l'application
     * @param login l'identifiant d'un employé
     * @param password le mot de passe d'un employé
     * @param success la méthode à appliquer en cas de réponse
     * @param error la méthode à appliquer en cas d'erreur
     */
    public void login(Context context, String login, String password, Consumer<JSONObject> success, Consumer<VolleyError> error) {
        JSONObject connexionData = new JSONObject();
        try {
            // On ajoute les informations de connexion
            connexionData.put("login", login);
            connexionData.put("password", password);
        } catch (JSONException e) {
            throw new RuntimeException(e);
        }

        JsonObjectRequest request = new JsonObjectRequest(Request.Method.POST, API_URL + "login", connexionData,
                success::accept,
                error::accept
        );
        getFileRequete(context).add(request);
    }

    /**
     * Envoi un rapport d'incident associé à une réservation
     * @param context La vue sur laquelle s'effectue l'action
     * @param apiKey la clé de l'api pour s'y connecter
     * @param idReservation l'id de la réservation où l'incident s'est déroulé
     * @param summary le nom de l'incident
     * @param description les détails de l'incident
     * @param severityId la sévérité de l'incident par rapport à la salle
     * @param isItContacted si oui ou non l'équipe IT doit contacter l'utilisateur
     * @param success Le traitement à effectuer si tout se passe bien
     * @param error Le traitement à effectuer si l'envoi se passe mal
     */
    public void sendReport(Context context, String apiKey, String idReservation,
                           String summary, String description, int severityId,
                           boolean isItContacted, Consumer<JSONObject> success,
                           Consumer<VolleyError> error) {

        JSONObject reportData = new JSONObject();

        try {
            reportData.put("resume", summary);
            reportData.put("description", description);
            reportData.put("incident", severityId);
            reportData.put("contact", isItContacted ? 1 : 0);
        } catch (JSONException e) {
            throw new RuntimeException(e);
        }

        JsonObjectRequest request = new JsonObjectRequest(Request.Method.POST
                , API_URL + "report/" + idReservation, reportData,
                success::accept,
                error::accept
        ){
            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();
                headers.put("api_key", apiKey);
                return headers;
            }
        };
        getFileRequete(context).add(request);
    }

    /**
     * Modifie un rapport d'incident associé à une réservation
     * @param context La vue sur laquelle s'effectue l'action
     * @param apiKey la clé de l'api pour s'y connecter
     * @param reportId l'id de l'incident
     * @param summary le nom de l'incident
     * @param description les détails de l'incident
     * @param severityId la sévérité de l'incident par rapport à la salle
     * @param success Le traitement à effectuer si tout se passe bien
     * @param error Le traitement à effectuer si l'envoi se passe mal
     */
    public void modifyReport(Context context, String apiKey, String reportId,
                           String summary, String description, int severityId,
                           boolean isItContacted,
                           Consumer<JSONObject> success,
                           Consumer<VolleyError> error) {

        JSONObject reportData = new JSONObject();

        try {
            reportData.put("resume", summary);
            reportData.put("description", description);
            reportData.put("incident", severityId);
            reportData.put("contact", isItContacted ? 1 : 0);
        } catch (JSONException e) {
            throw new RuntimeException(e);
        }

        JsonObjectRequest request = new JsonObjectRequest(Request.Method.PUT
                , API_URL + "edit/" + reportId, reportData,
                success::accept,
                error::accept
        ){
            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();
                headers.put("api_key", apiKey);
                return headers;
            }
        };
        getFileRequete(context).add(request);
    }

    public void deleteReport(int toDeleteReportId, Context context, String apiKey, Consumer<JSONArray> success, Consumer<VolleyError> error) {
        JsonArrayRequest request = new JsonArrayRequest(Request.Method.DELETE, API_URL + "supprimer/" + toDeleteReportId, null,
                success::accept,
                error::accept
        ) {
            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();
                headers.put("api_key", apiKey);
                return headers;
            }
        };
        getFileRequete(context).add(request);
    }

    /**
     * Récupère la liste des réservations d'un compte
     * @param context le contexte de l'application
     * @param apiKey la clé API associée au compte
     * @param success la méthode à appliquer en cas de réponse
     * @param error la méthode à appliquer en cas d'erreur
     */
    public void reservations(Context context, String apiKey, Consumer<JSONArray> success, Consumer<VolleyError> error) {
        JsonArrayRequest request = new JsonArrayRequest(Request.Method.GET, API_URL + "reservations", null,
                success::accept,
                error::accept
        ) {
            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();
                headers.put("api_key", apiKey);
                return headers;
            }
        };
        getFileRequete(context).add(request);
    }

    /**
     * Récupère la liste des signalements d'un compte
     * @param context le contexte de l'application
     * @param apiKey la clé API associée au compte
     * @param success la méthode à appliquer en cas de réponse
     * @param error la méthode à appliquer en cas d'erreur
     */
    public void signalements(Context context, String apiKey, String reservation,
                             Consumer<JSONArray> success, Consumer<VolleyError> error) {
        Log.i("Hello there", API_URL + "signalements"+reservation);
        JsonArrayRequest request = new JsonArrayRequest(Request.Method.GET, API_URL + "signalements" + reservation, null,
                success::accept,
                error::accept
        ) {
            @Override
            public Map<String, String> getHeaders() {
                Map<String, String> headers = new HashMap<>();
                headers.put("api_key", apiKey);
                return headers;
            }
        };
        getFileRequete(context).add(request);
    }

    /**
     * Renvoie la file d'attente pour les requêtes Web :
     * - si la file n'existe pas encore : elle est créée puis renvoyée
     * - si une file d'attente existe déjà : elle est renvoyée
     * On assure ainsi l'unicité de la file d'attente
     * @return RequestQueue une file d'attente pour les requêtes Volley
     */
    private RequestQueue getFileRequete(Context context) {
        if (fileRequete == null) {
            fileRequete = Volley.newRequestQueue(context);
        }
        // sinon
        return fileRequete;
    }
}

package com.example.incidandroid.model;

/**
 * Convertit un JSON en une réservation
 *             - idReservation   : L'id de la résa
 *             - reservationRoom : la salle réservée
 *             - reservationActivity : Activité de la réservation
 *             - reservationDate : date de résa
 *             - startHour      : heure de début de résa
 *             - endHour        : heure de fin de résa
 * Exemple :
 *
 *     {
 *             - idReservation   : R000001
 *             - reservationRoom : Salle picasso
 *             - reservationActivity : Réunion
 *             - reservationDate : 2025-12-31
 *             - startHour      : 12:00:00
 *             - endHour        : 17:00:00
 *     }
 */
public class Reservation {

    private String idReservation;
    private Integer idIncidentMax;
    private String reservationActivity;
    private String reservationRoom;
    private String reservationDate;
    private String startHour;
    private String endHour;
    private ReportSeverity reservationRoomStatus;

    public String getIdReservation() {
        return idReservation;
    }

    public String getReservationActivity() {
        return reservationActivity;
    }

    public String getReservationRoom() {
        return reservationRoom;
    }

    public String getReservationDate() {
        return reservationDate;
    }

    public String getStartHour() {
        return startHour;
    }

    public String getEndHour() {
        return endHour;
    }

    public void setReservationRoomStatus(ReportSeverity status) {
        this.reservationRoomStatus = status;
    }
    public String getReservationRoomStatus() {
        return reservationRoomStatus.getName();
    }

    public Integer getIdIncidentMax() {
        return idIncidentMax;
    }
}

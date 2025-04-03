package com.example.incidandroid.model;

public class Report {
    private int id;
    private String date;
    private int incident;
    private String resume;
    private String description;
    private String id_reservation;
    private String roomName;
    private String activityName;
    private String reservationDate;
    private String startHour;
    private String endHour;
    private int contact;

    public String getDate() {
        return date;
    }

    public int getIncident() {
        return incident;
    }

    public String getResume() {
        return resume;
    }

    public String getDescription() {
        return description;
    }

    public String getId_reservation() {
        return id_reservation;
    }

    public String getRoomName() {
        return roomName;
    }

    public String getActivityName() {
        return activityName;
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

    public int getId() {
        return id;
    }

    public int getContact() {
        return contact;
    }

    public void setContact(int contact) {
        this.contact = contact;
    }
}

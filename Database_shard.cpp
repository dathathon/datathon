#include<iostream>
#include<fstream>
#include<cstring>
#include<string>
#include<sstream>
#include<cstdlib>
#include<time.h>
#include <mysql_connection.h>
#include <cppconn/driver.h>
#include <cppconn/exception.h>
#include <cppconn/resultset.h>
#include <cppconn/statement.h>

using namespace std;

sql::Connection * ConnectToDB() {
    sql::Driver *driver;
    sql::Connection *con;

    /* Create a connection */
    driver = get_driver_instance();
    con = driver->connect("tcp://127.0.0.1:3306", "root", "123");
    /* Connect to the MySQL test database */
    con->setSchema("datathon");
    
    return con;
}

// Get DB connection.
sql::Connection *con = ConnectToDB();

void CreateTable(string table_name) {
    sql::ResultSet *res;
    sql::Statement *stmt;
    
    stmt = con->createStatement();
        
    string query = "CREATE TABLE " + table_name + " (";
    query += "BidId varchar(50), TrafficType varchar(50), PublisherId varchar(50), AppSiteId varchar(50), AppSiteCategory varchar(50), ";
    query += "Position varchar(50), BidFloor DECIMAL (5,2), Timestamp INTEGER, Age SMALLINT, Gender varchar(50), OS varchar(50), OSVersion varchar(50), ";
    query += "Model varchar(50), Manufacturer varchar(50), Carrier varchar(50), DeviceType varchar(50), DeviceId varchar(50), "; 
    query += "DeviceIP varchar(50), Country varchar(50), Latitude DECIMAL (5,2), Longitude DECIMAL (5,2), Zipcode INTEGER, GeoType varchar(50), ";
    query += "CampaignId INTEGER, CreativeId INTEGER, CreativeType SMALLINT, CreativeCategory varchar(50), ExchangeBid DECIMAL (5,2), Outcome varchar(50))";
    
    stmt->execute(query);
}

string CreateQuery(string table_name, string line) {
    
    string query = "INSERT INTO " + table_name + " VALUES (";
    
    char* buf = strdup(line.c_str());
    char *frag = strtok(buf, ",");
    while(frag != 0) {
        string str(frag);
        query += "'" + str + "',";
        frag = strtok(NULL, ",");
    }

    delete[] buf;
    
    query = query.substr(0, query.length()-1);
    query += ")";
    ////cout << "query: " << query << endl;
    return query;
}

string GetCategory(string line) {
    char* buf = strdup(line.c_str());
    char *frag = strtok(buf, ",");
    
    for(int i=0; i<4; i++) {
        frag = strtok(NULL, ",");
    }

    string category(frag);

    char chars[] = " &#/";

    for (unsigned int i = 0; i < strlen(chars); ++i)
    {
         // you need include <algorithm> to use general algorithms like std::remove()
        category.erase (std::remove(category.begin(), category.end(), chars[i]), category.end());
    }
    
    return category; 
}

int GetTime(string line) {
    char* buf = strdup(line.c_str());
    char *frag = strtok(buf, ",");
    for(int i=0; i<7; i++) {
        frag = strtok(NULL, ",");
    }
    int time_hour = atoi(frag);
    
    time_t rawtime = (time_t)time_hour;
    time (&rawtime);
    
    char * time_string = ctime(&rawtime);
    
    char *str_fragments = strtok(time_string, " ");
    for(int i=0; i<3; i++) {
        str_fragments = strtok(NULL, ",");
    }
    char * time_value = str_fragments;
    
    char *time_frag = strtok(time_value, ":");
    delete[] buf;
    return atoi(time_frag);
}

void PushInCategoryTable(string line) {
    sql::ResultSet *res;
    sql::Statement *stmt;
    cout << "Inside PushInCategoryTable 1" << endl;
    string category = GetCategory(line);
    cout << "Inside PushInCategoryTable 2: " << category << endl;
    stmt = con->createStatement();
    
    size_t row_count;
    // Check for category table.
    string query = "SHOW TABLES LIKE '" + category + "'";
    cout << "query 1: " << query << endl;
    res = stmt->executeQuery(query);
    if((row_count = res->rowsCount()) == 0) {
        CreateTable(category);    
    }
    
    query = CreateQuery(category, line);
    cout << "query 2: " << query << endl;
    stmt->execute(query);
}

void PushInTimeTable(string line) {
    sql::ResultSet *res;
    sql::Statement *stmt;
    int time_hour = GetTime(line);
    stmt = con->createStatement();
    
    size_t row_count;
    // Check for application table.
    stringstream str;
    str << time_hour; 
    string query = "SHOW TABLES LIKE '" + str.str() + "'";
    res = stmt->executeQuery(query);
    if((row_count = res->rowsCount()) == 0) {
        CreateTable(str.str());    
    }
    
    query = CreateQuery(str.str(), line);
    stmt->execute(query);
}

void PushInCountryTable(string line) {
    sql::ResultSet *res;
    sql::Statement *stmt;
    
    stmt = con->createStatement();
    
    size_t row_count;
    // Check for application table.
    string query = "SHOW TABLES LIKE 'usa'";
    ////cout << "In Query:" << query << endl;
    res = stmt->executeQuery(query);
    if((row_count = res->rowsCount()) == 0) {
        CreateTable("usa");
    }
        
    string table_name = "usa";
    query = CreateQuery(table_name, line);
    cout << "query: " << query << endl;
    //res = stmt->executeQuery(query);
    stmt->execute(query);
    cout << "In PushInCountryTable" << endl;
}

void PushInCategoryTimeTable(string line) {
    sql::ResultSet *res;
    sql::Statement *stmt;
    
    stmt = con->createStatement();
    stringstream str;
    str << GetTime(line);
    string table_name = GetCategory(line).substr(0, 20) + "_" + str.str();
    
    size_t row_count;
    // Check for application table.
    string query = "SHOW TABLES LIKE '" + table_name + "'";
    res = stmt->executeQuery(query);
    if((row_count = res->rowsCount()) == 0) {
        CreateTable(table_name);
    }
        
    cout << "before query" << endl;
    query = CreateQuery(table_name, line);
    cout << "Category query" << query << endl;
    stmt->execute(query);
}

void PushInDB(string line) {
    ////cout << "In PushInDB" << endl;
    PushInCountryTable(line);
    PushInCategoryTable(line);
    //PushInTimeTable(line);
    //PushInCategoryTimeTable(line);
}

bool CountryIsValid(string line) {

    char* buf = strdup(line.c_str());
    char *frag = strtok(buf, ",");

    for(int i=0; i<18; i++) {
        frag = strtok(NULL, ",");
    }
    
    string str(frag);
    //cout << "In CountryIsValid:" << str << endl;
    if(str == "USA" || str == "US") {
        delete[] buf;
        return true;
    } else {
        delete[] buf;
        return false;
    };
}

int main(int argc, char *argv[]) {
    string file_name;
    file_name = argv[1];

    //cout << "file name:" << file_name << endl;
    
    ifstream data_file(file_name.c_str());
    //cout << " after name:" << endl;
    
    string line;
    while(getline(data_file, line)) {
        ////cout << "line:" << line << endl;
        if(CountryIsValid(line)) {
            PushInDB(line);
        }
    }
}

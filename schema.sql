SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `Administrator` (
  `Username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `AssignTo` (
  `StaffUsername` varchar(20) NOT NULL,
  `EventName` varchar(20) NOT NULL,
  `StartDate` datetime NOT NULL,
  `SiteName` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Connect` (
  `SiteName` varchar(20) NOT NULL,
  `TransitType` varchar(5) NOT NULL,
  `TransitRoute` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Employee` (
  `Username` varchar(20) NOT NULL,
  `Phone` varchar(10) NOT NULL,
  `EmployeeID` varchar(9) NOT NULL,
  `EmployeeAddress` varchar(40) DEFAULT NULL,
  `EmployeeCity` varchar(20) DEFAULT NULL,
  `EmployeeState` varchar(5) DEFAULT NULL,
  `EmployeeZipcode` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Event` (
  `EventName` varchar(20) NOT NULL,
  `StartDate` datetime NOT NULL,
  `SiteName` varchar(20) NOT NULL,
  `EndDate` datetime NOT NULL,
  `EventPrice` double NOT NULL,
  `Capacity` int(11) NOT NULL,
  `Description` text NOT NULL,
  `MinStaffRequired` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Manager` (
  `Username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Site` (
  `SiteName` varchar(20) NOT NULL,
  `SiteAddress` varchar(40) DEFAULT NULL,
  `SiteZipcode` varchar(5) NOT NULL,
  `OpenEveryday` tinyint(1) NOT NULL,
  `ManagerUsername` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Staff` (
  `Username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `TakeTransit` (
  `Username` varchar(20) NOT NULL,
  `TransitType` varchar(5) NOT NULL,
  `TransitRoute` varchar(20) NOT NULL,
  `TransitDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Transit` (
  `TransitType` varchar(5) NOT NULL,
  `TransitRoute` varchar(20) NOT NULL,
  `TransitPrice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `User` (
  `Username` varchar(20) NOT NULL,
  `Password` varchar(60) NOT NULL,
  `Status` varchar(20) NOT NULL,
  `Firstname` varchar(20) NOT NULL,
  `Lastname` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `UserEmail` (
  `Username` varchar(20) NOT NULL,
  `Email` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `VisitEvent` (
  `VisitorUsername` varchar(20) NOT NULL,
  `EventName` varchar(20) NOT NULL,
  `SiteName` varchar(20) NOT NULL,
  `StartDate` datetime NOT NULL,
  `VisitEventDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Visitor` (
  `Username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `VisitSite` (
  `VisitorUsername` varchar(20) NOT NULL,
  `SiteName` varchar(20) NOT NULL,
  `VisitSiteDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `Administrator`
  ADD PRIMARY KEY (`Username`);

ALTER TABLE `AssignTo`
  ADD KEY `StaffUsername` (`StaffUsername`),
  ADD KEY `EventName` (`EventName`,`StartDate`,`SiteName`);

ALTER TABLE `Connect`
  ADD KEY `SiteName` (`SiteName`),
  ADD KEY `Type` (`TransitType`,`TransitRoute`);

ALTER TABLE `Employee`
  ADD PRIMARY KEY (`Username`);

ALTER TABLE `Event`
  ADD PRIMARY KEY (`EventName`,`StartDate`,`SiteName`) USING BTREE,
  ADD KEY `SiteName` (`SiteName`);

ALTER TABLE `Manager`
  ADD PRIMARY KEY (`Username`);

ALTER TABLE `Site`
  ADD PRIMARY KEY (`SiteName`),
  ADD KEY `ManagedBy` (`ManagerUsername`);

ALTER TABLE `Staff`
  ADD PRIMARY KEY (`Username`);

ALTER TABLE `TakeTransit`
  ADD KEY `Date` (`TransitDate`),
  ADD KEY `Username` (`Username`),
  ADD KEY `Type` (`TransitType`,`TransitRoute`);

ALTER TABLE `Transit`
  ADD PRIMARY KEY (`TransitType`,`TransitRoute`);

ALTER TABLE `User`
  ADD PRIMARY KEY (`Username`);

ALTER TABLE `UserEmail`
  ADD PRIMARY KEY (`Email`) USING BTREE,
  ADD KEY `Username` (`Username`);

ALTER TABLE `VisitEvent`
  ADD KEY `Username` (`VisitorUsername`),
  ADD KEY `VisitEventDate` (`VisitEventDate`),
  ADD KEY `EventName_2` (`EventName`,`StartDate`,`SiteName`);

ALTER TABLE `Visitor`
  ADD PRIMARY KEY (`Username`);

ALTER TABLE `VisitSite`
  ADD KEY `Date` (`VisitSiteDate`),
  ADD KEY `Username` (`VisitorUsername`),
  ADD KEY `SiteName` (`SiteName`);


ALTER TABLE `Administrator`
  ADD CONSTRAINT `administrator_1` FOREIGN KEY (`Username`) REFERENCES `Employee` (`Username`);

ALTER TABLE `AssignTo`
  ADD CONSTRAINT `assignto_ibfk_1` FOREIGN KEY (`StaffUsername`) REFERENCES `Staff` (`Username`),
  ADD CONSTRAINT `assignto_ibfk_2` FOREIGN KEY (`EventName`,`StartDate`,`SiteName`) REFERENCES `Event` (`EventName`, `StartDate`, `SiteName`);

ALTER TABLE `Connect`
  ADD CONSTRAINT `connect_1` FOREIGN KEY (`SiteName`) REFERENCES `Site` (`SiteName`),
  ADD CONSTRAINT `connect_2` FOREIGN KEY (`TransitType`,`TransitRoute`) REFERENCES `Transit` (`TransitType`, `TransitRoute`);

ALTER TABLE `Employee`
  ADD CONSTRAINT `employee_1` FOREIGN KEY (`Username`) REFERENCES `User` (`Username`);

ALTER TABLE `Event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`SiteName`) REFERENCES `Site` (`SiteName`);

ALTER TABLE `Manager`
  ADD CONSTRAINT `manager_1` FOREIGN KEY (`Username`) REFERENCES `Employee` (`Username`);

ALTER TABLE `Site`
  ADD CONSTRAINT `site_1` FOREIGN KEY (`ManagerUsername`) REFERENCES `Manager` (`Username`);

ALTER TABLE `Staff`
  ADD CONSTRAINT `staff_1` FOREIGN KEY (`Username`) REFERENCES `Employee` (`Username`);

ALTER TABLE `TakeTransit`
  ADD CONSTRAINT `take_1` FOREIGN KEY (`Username`) REFERENCES `User` (`Username`),
  ADD CONSTRAINT `take_2` FOREIGN KEY (`TransitType`,`TransitRoute`) REFERENCES `Transit` (`TransitType`, `TransitRoute`);

ALTER TABLE `UserEmail`
  ADD CONSTRAINT `useremail_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `User` (`Username`);

ALTER TABLE `VisitEvent`
  ADD CONSTRAINT `visit_event_1` FOREIGN KEY (`VisitorUsername`) REFERENCES `Visitor` (`Username`),
  ADD CONSTRAINT `visitevent_ibfk_1` FOREIGN KEY (`EventName`,`StartDate`,`SiteName`) REFERENCES `Event` (`EventName`, `StartDate`, `SiteName`);

ALTER TABLE `Visitor`
  ADD CONSTRAINT `visitor_1` FOREIGN KEY (`Username`) REFERENCES `User` (`Username`);

ALTER TABLE `VisitSite`
  ADD CONSTRAINT `visit_site_1` FOREIGN KEY (`VisitorUsername`) REFERENCES `User` (`Username`),
  ADD CONSTRAINT `visit_site_2` FOREIGN KEY (`SiteName`) REFERENCES `Site` (`SiteName`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

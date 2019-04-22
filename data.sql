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

INSERT INTO `Administrator` (`Username`) VALUES
('james.smith');

CREATE TABLE `AssignTo` (
  `StaffUsername` varchar(20) NOT NULL,
  `EventName` varchar(40) NOT NULL,
  `StartDate` date NOT NULL,
  `SiteName` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `AssignTo` (`StaffUsername`, `EventName`, `StartDate`, `SiteName`) VALUES
('robert.smith', 'Eastside Trail', '2019-02-04', 'Inman Park'),
('staff1', 'Eastside Trail', '2019-03-01', 'Inman Park'),
('michael.smith', 'Bus Tour', '2019-02-01', 'Inman Park'),
('staff2', 'Bus Tour', '2019-02-01', 'Inman Park'),
('michael.smith', 'Bus Tour', '2019-02-08', 'Inman Park'),
('robert.smith', 'Bus Tour', '2019-02-08', 'Inman Park'),
('robert.smith', 'Private Bus Tour', '2019-02-01', 'Inman Park'),
('michael.smith', 'Eastside Trail', '2019-02-04', 'Piedmont Park'),
('staff1', 'Eastside Trail', '2019-02-04', 'Piedmont Park'),
('staff1', 'Westside Trail', '2019-02-18', 'Westview Cemetery'),
('staff3', 'Westside Trail', '2019-02-18', 'Westview Cemetery'),
('michael.smith', 'Eastside Trail', '2019-02-13', 'Historic Fourth Ward Park'),
('staff3', 'Arboretum Walking Tour', '2019-02-08', 'Inman Park'),
('staff2', 'Eastside Trail', '2019-02-04', 'Inman Park');

CREATE TABLE `Connect` (
  `SiteName` varchar(40) NOT NULL,
  `TransitType` varchar(5) NOT NULL,
  `TransitRoute` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `Connect` (`SiteName`, `TransitType`, `TransitRoute`) VALUES
('Inman Park', 'MARTA', 'Blue'),
('Piedmont Park', 'MARTA', 'Blue'),
('Historic Fourth Ward Park', 'MARTA', 'Blue'),
('Westview Cemetery', 'MARTA', 'Blue'),
('Inman Park', 'Bus', '152'),
('Piedmont Park', 'Bus', '152'),
('Historic Fourth Ward Park', 'Bus', '152'),
('Piedmont Park', 'Bike', 'Relay'),
('Historic Fourth Ward Park', 'Bike', 'Relay');

CREATE TABLE `Employee` (
  `Username` varchar(20) NOT NULL,
  `Phone` varchar(10) NOT NULL,
  `EmployeeID` varchar(9) DEFAULT NULL,
  `EmployeeAddress` varchar(40) NOT NULL,
  `EmployeeCity` varchar(20) NOT NULL,
  `EmployeeState` varchar(5) NOT NULL,
  `EmployeeZipcode` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `Employee` (`Username`, `Phone`, `EmployeeID`, `EmployeeAddress`, `EmployeeCity`, `EmployeeState`, `EmployeeZipcode`) VALUES
('david.smith', '5124776435', '000000005', '350 Ferst Drive', 'Atlanta', 'GA', '30332'),
('james.smith', '4043721234', '000000001', '123 East Main Street', 'Rochester', 'NY', '14604'),
('manager1', '8045126767', '000000006', '123 East Main Street', 'Rochester', 'NY', '14604'),
('manager2', '9876543210', '000000007', '123 East Main Street', 'Rochester', 'NY', '14604'),
('manager3', '5432167890', '000000008', '350 Ferst Drive', 'Atlanta', 'GA', '30332'),
('manager4', '8053467565', '000000009', '123 East Main Street', 'Columbus', 'OH', '43215'),
('manager5', '8031446782', '000000010', '801 Atlantic Drive', 'Atlanta', 'GA', '30332'),
('maria.garcia', '7890123456', '000000004', '123 East Main Street', 'Richland', 'PA', '17987'),
('michael.smith', '4043726789', '000000002', '350 Ferst Drive', 'Atlanta', 'GA', '30332'),
('robert.smith', '1234567890', '000000003', '123 East Main Street', 'Columbus', 'OH', '43215'),
('staff1', '8024456765', '000000011', '266 Ferst Drive Northwest', 'Atlanta', 'GA', '30332'),
('staff2', '8888888888', '000000012', '266 Ferst Drive Northwest', 'Atlanta', 'GA', '30332'),
('staff3', '3333333333', '000000013', '801 Atlantic Drive', 'Atlanta', 'GA', '30332');

CREATE TABLE `Event` (
  `EventName` varchar(40) NOT NULL,
  `StartDate` date NOT NULL,
  `SiteName` varchar(40) NOT NULL,
  `EndDate` date NOT NULL,
  `EventPrice` double NOT NULL,
  `Capacity` int(11) NOT NULL,
  `Description` text NOT NULL,
  `MinStaffRequired` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `Event` (`EventName`, `StartDate`, `SiteName`, `EndDate`, `EventPrice`, `Capacity`, `Description`, `MinStaffRequired`) VALUES
('Arboretum Walking Tour', '2019-02-08', 'Inman Park', '2019-02-11', 5, 5, 'Official Atlanta BeltLine Arboretum Walking Tours provide an up-close view of the Westside Trail and the Atlanta BeltLine Arboretum led by Trees Atlanta Docents. The one and a half hour tours step off at at 10am (Oct thru May), and 9am (June thru September). Departure for all tours is from Rose Circle Park near Brown Middle School. More details at: https://beltline.org/visit/atlanta-beltline-tours/#arboretum-walking', 1),
('Bus Tour', '2019-02-01', 'Inman Park', '2019-02-02', 25, 6, 'The Atlanta BeltLine Partnershipâ€™s tour program operates with a natural gas-powered, ADA accessible tour bus funded through contributions from 10th & Monroe, LLC, SunTrust Bank Trusteed Foundations â€“ Florence C. and Harry L. English Memorial Fund and Thomas Guy Woolford Charitable Trust, and AGL Resources', 2),
('Bus Tour', '2019-02-08', 'Inman Park', '2019-02-10', 25, 6, 'The Atlanta BeltLine Partnershipâ€™s tour program operates with a natural gas-powered, ADA accessible tour bus funded through contributions from 10th & Monroe, LLC, SunTrust Bank Trusteed Foundations â€“ Florence C. and Harry L. English Memorial Fund and Thomas Guy Woolford Charitable Trust, and AGL Resources', 2),
('Eastside Trail', '2019-02-04', 'Inman Park', '2019-02-05', 0, 99999, 'A combination of multi-use trail and linear greenspace, the Eastside Trail was the first finished section of the Atlanta BeltLine trail in the old rail corridor. The Eastside Trail, which was funded by a combination of public and private philanthropic sources, runs from the tip of Piedmont Park to Reynoldstown. More details at https://beltline.org/explore-atlanta-beltline-trails/eastside-trail/', 1),
('Eastside Trail', '2019-02-04', 'Piedmont Park', '2019-02-05', 0, 99999, 'A combination of multi-use trail and linear greenspace, the Eastside Trail was the first finished section of the Atlanta BeltLine trail in the old rail corridor. The Eastside Trail, which was funded by a combination of public and private philanthropic sources, runs from the tip of Piedmont Park to Reynoldstown. More details at https://beltline.org/explore-atlanta-beltline-trails/eastside-trail/', 1),
('Eastside Trail', '2019-02-13', 'Historic Fourth Ward Park', '2019-02-14', 0, 99999, 'A combination of multi-use trail and linear greenspace, the Eastside Trail was the first finished section of the Atlanta BeltLine trail in the old rail corridor. The Eastside Trail, which was funded by a combination of public and private philanthropic sources, runs from the tip of Piedmont Park to Reynoldstown. More details at https://beltline.org/explore-atlanta-beltline-trails/eastside-trail/', 1),
('Eastside Trail', '2019-03-01', 'Inman Park', '2019-03-02', 0, 99999, 'A combination of multi-use trail and linear greenspace, the Eastside Trail was the first finished section of the Atlanta BeltLine trail in the old rail corridor. The Eastside Trail, which was funded by a combination of public and private philanthropic sources, runs from the tip of Piedmont Park to Reynoldstown. More details at https://beltline.org/explore-atlanta-beltline-trails/eastside-trail/', 1),
('Official Atlanta BeltLine Bike Tour', '2019-02-09', 'Atlanta Beltline Center', '2019-02-14', 5, 5, 'These tours will include rest stops highlighting assets and points of interest along the Atlanta BeltLine. Staff will lead the rides, and each group will have a ride sweep to help with any unexpected mechanical difficulties.', 1),
('Private Bus Tour', '2019-02-01', 'Inman Park', '2019-02-02', 40, 4, 'Private tours are available most days, pending bus and tour guide availability. Private tours can accommodate up to 4 guests per tour, and are subject to a tour fee (nonprofit rates are available). As a nonprofit organization with limited resources, we are unable to offer free private tours. We thank you for your support and your understanding as we try to provide as many private group tours as possible. The Atlanta BeltLine Partnershipâ€™s tour program operates with a natural gas-powered, ADA accessible tour bus funded through contributions from 10th & Monroe, LLC, SunTrust Bank Trusteed Foundations â€“ Florence C. and Harry L. English Memorial Fund and Thomas Guy Woolford Charitable Trust, and AGL Resources', 1),
('Westside Trail', '2019-02-18', 'Westview Cemetery', '2019-02-21', 0, 99999, 'The Westside Trail is a free amenity that offers a bicycle and pedestrian-safe corridor with a 14-foot-wide multi-use trail surrounded by mature trees and grasses thanks to Trees Atlantaâ€™s Arboretum. With 16 points of entry, 14 of which will be ADA-accessible with ramp and stair systems, the trail provides numerous access points for people of all abilities. More details at: https://beltline.org/explore-atlanta-beltline-trails/westside-trail/', 1);

CREATE TABLE `Manager` (
  `Username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `Manager` (`Username`) VALUES
('david.smith'),
('manager1'),
('manager2'),
('manager3'),
('manager4'),
('manager5'),
('maria.garcia');

CREATE TABLE `Site` (
  `SiteName` varchar(40) NOT NULL,
  `SiteAddress` varchar(40) DEFAULT NULL,
  `SiteZipcode` varchar(5) NOT NULL,
  `OpenEveryday` tinyint(1) NOT NULL,
  `ManagerUsername` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `Site` (`SiteName`, `SiteAddress`, `SiteZipcode`, `OpenEveryday`, `ManagerUsername`) VALUES
('Atlanta Beltline Center', '112 Krog Street Northeast', '30307', 0, 'manager3'),
('Historic Fourth Ward Park', '680 Dallas Street Northeast', '30308', 1, 'manager4'),
('Inman Park', '', '30307', 1, 'david.smith'),
('Piedmont Park', '400 Park Drive Northeast', '30306', 1, 'manager2'),
('Westview Cemetery', '1680 Westview Drive Southwest', '30310', 0, 'manager5');

CREATE TABLE `Staff` (
  `Username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `Staff` (`Username`) VALUES
('michael.smith'),
('robert.smith'),
('staff1'),
('staff2'),
('staff3');

CREATE TABLE `TakeTransit` (
  `Username` varchar(20) NOT NULL,
  `TransitType` varchar(5) NOT NULL,
  `TransitRoute` varchar(20) NOT NULL,
  `TransitDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `TakeTransit` (`Username`, `TransitType`, `TransitRoute`, `TransitDate`) VALUES
('manager2', 'MARTA', 'Blue', '2019-03-20'),
('manager2', 'Bus', '152', '2019-03-20'),
('manager3', 'Bike', 'Relay', '2019-03-20'),
('manager2', 'MARTA', 'Blue', '2019-03-21'),
('maria.hernandez', 'Bus', '152', '2019-03-20'),
('maria.hernandez', 'Bike', 'Relay', '2019-03-20'),
('manager2', 'MARTA', 'Blue', '2019-03-22'),
('maria.hernandez', 'Bus', '152', '2019-03-22'),
('mary.smith', 'Bike', 'Relay', '2019-03-23'),
('visitor1', 'MARTA', 'Blue', '2019-03-21');

CREATE TABLE `Transit` (
  `TransitType` varchar(5) NOT NULL,
  `TransitRoute` varchar(20) NOT NULL,
  `TransitPrice` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `Transit` (`TransitType`, `TransitRoute`, `TransitPrice`) VALUES
('Bike', 'Relay', 1),
('Bus', '152', 2),
('MARTA', 'Blue', 2);

CREATE TABLE `User` (
  `Username` varchar(20) NOT NULL,
  `Password` varchar(60) NOT NULL,
  `Status` varchar(20) NOT NULL,
  `Firstname` varchar(20) NOT NULL,
  `Lastname` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `User` (`Username`, `Password`, `Status`, `Firstname`, `Lastname`) VALUES
('david.smith', '$2y$10$rTEDIIXHT/szyivBCOBd8.s9WaYmDEDuFyB3t5YQ.MhJsAVOZfB6G', 'approved', 'David', 'Smith'),
('james.smith', '$2y$10$BMOlAD3C0qVmHqllqixC0.T5LJzaKg2Us3CdD4DCOHp0b/SQpEhsy', 'approved', 'James', 'Smith'),
('manager1', '$2y$10$XH5RElCJ0O9QvxvZRcd4fOxCj6O1KS7bCUQuhGjTSUPEAAFOxp7Wi', 'pending', 'Manager', 'One'),
('manager2', '$2y$10$cAGt4THqwnAMq4J4eQSB2.bVAo1yOf6Db3Tcxu9CtHCjQoHyWfvgm', 'approved', 'Manager', 'Two'),
('manager3', '$2y$10$a9KkkNlnzFfZDkWe2YcBEeWLgK/53ttjIi.nZ/jCBi35RWFZFuKKe', 'approved', 'Manager', 'Three'),
('manager4', '$2y$10$orzM62segM29WKqOl9npPOicgBZaFmTrZMjS/TGZrPgkgpkQ88gnG', 'approved', 'Manager', 'Four'),
('manager5', '$2y$10$DLphBF3k3q0IDAR84GpMIuKJeZOwVFG7/drInMXjhrRKyBCqNN.9e', 'approved', 'Manager', 'Five'),
('maria.garcia', '$2y$10$/vEFbPLo5/orXRhAQbOG7.DPQ/hsKv/udMiJLcaUiqVvkTGjRojga', 'approved', 'Maria', 'Garcia'),
('maria.hernandez', '$2y$10$gftXXDICOTX59RpCu0P3d.kyhjhwNgORlfut8Zl/u0VekmnYBllam', 'approved', 'Maria', 'Hernandez'),
('maria.rodriguez', '$2y$10$EaF47Stjq.lLmQ7ijaGyPOhEs/JVQaSFDgtETQbuH7y1hjY1R7Mzy', 'declined', 'Maria', 'Rodriguez'),
('mary.smith', '$2y$10$8Xx4uURCMSMDVmSdOaMVfua/N7fgEAMnAU.Ax4V3pcZIq8zj3K0Bm', 'approved', 'Mary', 'Smith'),
('michael.smith', '$2y$10$OlW4G25K/eokuVJfC9vbv.IL8dgmJp37ZPXUt41DCPrV5WxLzQGq2', 'approved', 'Michael', 'Smith'),
('robert.smith', '$2y$10$e8Fkn72M3pJODiGci.MdIurbJDnMS2Kz.mkYRFuDP7TL6HKM149ey', 'approved', 'Robert', 'Smith'),
('staff1', '$2y$10$DRdFNrq5Z92NXapTTdR14O1TMXftb/L9tRQQXH1bNX30D8l/6E/wa', 'approved', 'Staff', 'One'),
('staff2', '$2y$10$gd1BMtqpVwACm1FBbLKCkO.cbZl3Oq9JOXxXoISTSbpS9jY/SkgLW', 'approved', 'Staff', 'Two'),
('staff3', '$2y$10$bt1iTjPZEwZBUtzrWR8mBu3wci0N..2alhH7cRX6Aj6bw1QjW33Tq', 'approved', 'Staff', 'Three'),
('user1', '$2y$10$6Uu7a9nmx3MYY67bo1nLvulCg/NUjeo9PuyXsmlgozdcIOUEH4mga', 'pending', 'User', 'One'),
('visitor1', '$2y$10$h1DaKDg4WyleompqMavQOeuKxMq4jznid6tohGNX3gEiuGSCwmhsG', 'approved', 'Visitor', 'One');

CREATE TABLE `UserEmail` (
  `Username` varchar(20) NOT NULL,
  `Email` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `UserEmail` (`Username`, `Email`) VALUES
('david.smith', 'dsmith@outlook.com'),
('james.smith', 'jsmith@gatech.edu'),
('james.smith', 'jsmith@gmail.com'),
('james.smith', 'jsmith@hotmail.com'),
('james.smith', 'jsmith@outlook.com'),
('manager1', 'm1@beltline.com'),
('manager2', 'm2@beltline.com'),
('manager3', 'm3@beltline.com'),
('manager4', 'm4@beltline.com'),
('manager5', 'm5@beltline.com'),
('maria.garcia', 'mgarcia@gatech.edu'),
('maria.garcia', 'mgarcia@yahoo.com'),
('maria.hernandez', 'mh123@gmail.com'),
('maria.hernandez', 'mh@gatech.edu'),
('maria.rodriguez', 'mrodriguez@gmail.com'),
('mary.smith', 'mary@outlook.com'),
('michael.smith', 'msmith@gmail.com'),
('robert.smith', 'rsmith@hotmail.com'),
('staff1', 's1@beltline.com'),
('staff2', 's2@beltline.com'),
('staff3', 's3@beltline.com'),
('user1', 'u1@beltline.com'),
('visitor1', 'v1@beltline.com');

CREATE TABLE `VisitEvent` (
  `VisitorUsername` varchar(20) NOT NULL,
  `EventName` varchar(40) NOT NULL,
  `SiteName` varchar(40) NOT NULL,
  `StartDate` date NOT NULL,
  `VisitEventDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `VisitEvent` (`VisitorUsername`, `EventName`, `SiteName`, `StartDate`, `VisitEventDate`) VALUES
('mary.smith', 'Bus Tour', 'Inman Park', '2019-02-01', '2019-02-01'),
('maria.garcia', 'Bus Tour', 'Inman Park', '2019-02-01', '2019-02-02'),
('manager2', 'Bus Tour', 'Inman Park', '2019-02-01', '2019-02-02'),
('manager4', 'Bus Tour', 'Inman Park', '2019-02-01', '2019-02-01'),
('manager5', 'Bus Tour', 'Inman Park', '2019-02-01', '2019-02-02'),
('staff2', 'Bus Tour', 'Inman Park', '2019-02-01', '2019-02-02'),
('mary.smith', 'Westside Trail', 'Westview Cemetery', '2019-02-18', '2019-02-19'),
('mary.smith', 'Private Bus Tour', 'Inman Park', '2019-02-01', '2019-02-01'),
('mary.smith', 'Private Bus Tour', 'Inman Park', '2019-02-01', '2019-02-02'),
('mary.smith', 'Official Atlanta BeltLine Bike Tour', 'Atlanta BeltLine Center', '2019-02-09', '2019-02-10'),
('mary.smith', 'Arboretum Walking Tour', 'Inman Park', '2019-02-08', '2019-02-10'),
('mary.smith', 'Eastside Trail', 'Piedmont Park', '2019-02-04', '2019-02-04'),
('mary.smith', 'Eastside Trail', 'Historic Fourth Ward Park', '2019-02-13', '2019-02-13'),
('mary.smith', 'Eastside Trail', 'Historic Fourth Ward Park', '2019-02-13', '2019-02-14'),
('visitor1', 'Eastside Trail', 'Historic Fourth Ward Park', '2019-02-13', '2019-02-14'),
('visitor1', 'Official Atlanta BeltLine Bike Tour', 'Atlanta BeltLine Center', '2019-02-09', '2019-02-10'),
('visitor1', 'Westside Trail', 'Westview Cemetery', '2019-02-18', '2019-02-19');

CREATE TABLE `Visitor` (
  `Username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `Visitor` (`Username`) VALUES
('manager2'),
('manager4'),
('manager5'),
('maria.garcia'),
('maria.rodriguez'),
('mary.smith'),
('michael.smith'),
('staff2'),
('staff3'),
('visitor1');

CREATE TABLE `VisitSite` (
  `VisitorUsername` varchar(20) NOT NULL,
  `SiteName` varchar(40) NOT NULL,
  `VisitSiteDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `VisitSite` (`VisitorUsername`, `SiteName`, `VisitSiteDate`) VALUES
('mary.smith', 'Inman Park', '2019-02-01'),
('mary.smith', 'Inman Park', '2019-02-02'),
('mary.smith', 'Inman Park', '2019-02-03'),
('mary.smith', 'Atlanta Beltline Center', '2019-02-01'),
('mary.smith', 'Atlanta Beltline Center', '2019-02-10'),
('mary.smith', 'Historic Fourth Ward Park', '2019-02-02'),
('mary.smith', 'Piedmont Park', '2019-02-02'),
('visitor1', 'Piedmont Park', '2019-02-11'),
('visitor1', 'Atlanta Beltline Center', '2019-02-13'),
('visitor1', 'Historic Fourth Ward Park', '2019-02-11'),
('visitor1', 'Westview Cemetery', '2019-02-06'),
('visitor1', 'Inman Park', '2019-02-01'),
('visitor1', 'Piedmont Park', '2019-02-01'),
('visitor1', 'Atlanta Beltline Center', '2019-02-09');


ALTER TABLE `Administrator`
  ADD PRIMARY KEY (`Username`);

ALTER TABLE `AssignTo`
  ADD KEY `EventName` (`EventName`,`StartDate`,`SiteName`),
  ADD KEY `assignto_ibfk_1` (`StaffUsername`);

ALTER TABLE `Connect`
  ADD KEY `SiteName` (`SiteName`),
  ADD KEY `Type` (`TransitType`,`TransitRoute`);

ALTER TABLE `Employee`
  ADD PRIMARY KEY (`Username`);

ALTER TABLE `Event`
  ADD PRIMARY KEY (`EventName`,`StartDate`,`SiteName`) USING BTREE,
  ADD KEY `event_ibfk_1` (`SiteName`);

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
  ADD KEY `useremail_ibfk_1` (`Username`);

ALTER TABLE `VisitEvent`
  ADD KEY `Username` (`VisitorUsername`),
  ADD KEY `VisitEventDate` (`VisitEventDate`),
  ADD KEY `EventName` (`EventName`,`StartDate`,`SiteName`);

ALTER TABLE `Visitor`
  ADD PRIMARY KEY (`Username`);

ALTER TABLE `VisitSite`
  ADD KEY `Date` (`VisitSiteDate`),
  ADD KEY `Username` (`VisitorUsername`),
  ADD KEY `SiteName` (`SiteName`);


ALTER TABLE `Administrator`
  ADD CONSTRAINT `administrator_1` FOREIGN KEY (`Username`) REFERENCES `Employee` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `AssignTo`
  ADD CONSTRAINT `assignto_ibfk_1` FOREIGN KEY (`StaffUsername`) REFERENCES `Staff` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assignto_ibfk_2` FOREIGN KEY (`EventName`,`StartDate`,`SiteName`) REFERENCES `Event` (`EventName`, `StartDate`, `SiteName`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `Connect`
  ADD CONSTRAINT `connect_1` FOREIGN KEY (`SiteName`) REFERENCES `Site` (`SiteName`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `connect_2` FOREIGN KEY (`TransitType`,`TransitRoute`) REFERENCES `Transit` (`TransitType`, `TransitRoute`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `Employee`
  ADD CONSTRAINT `employee_1` FOREIGN KEY (`Username`) REFERENCES `User` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `Event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`SiteName`) REFERENCES `Site` (`SiteName`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `Manager`
  ADD CONSTRAINT `manager_1` FOREIGN KEY (`Username`) REFERENCES `Employee` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `Site`
  ADD CONSTRAINT `site_1` FOREIGN KEY (`ManagerUsername`) REFERENCES `Manager` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `Staff`
  ADD CONSTRAINT `staff_1` FOREIGN KEY (`Username`) REFERENCES `Employee` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `TakeTransit`
  ADD CONSTRAINT `take_1` FOREIGN KEY (`Username`) REFERENCES `User` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `take_2` FOREIGN KEY (`TransitType`,`TransitRoute`) REFERENCES `Transit` (`TransitType`, `TransitRoute`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `UserEmail`
  ADD CONSTRAINT `useremail_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `User` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `VisitEvent`
  ADD CONSTRAINT `visit_event_1` FOREIGN KEY (`VisitorUsername`) REFERENCES `Visitor` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `visitevent_ibfk_1` FOREIGN KEY (`EventName`,`StartDate`,`SiteName`) REFERENCES `Event` (`EventName`, `StartDate`, `SiteName`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `Visitor`
  ADD CONSTRAINT `visitor_1` FOREIGN KEY (`Username`) REFERENCES `User` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `VisitSite`
  ADD CONSTRAINT `visit_site_1` FOREIGN KEY (`VisitorUsername`) REFERENCES `User` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `visit_site_2` FOREIGN KEY (`SiteName`) REFERENCES `Site` (`SiteName`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { DashboardLayout } from "@/components/layout/DashboardLayout";
import { StatCard } from "@/components/dashboard/StatCard";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Badge } from "@/components/ui/badge";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from "@/components/ui/alert-dialog";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { GraduationCap, UserPlus, Search, MoreVertical, Mail, Phone, BookOpen, Award, Edit, Trash2, Calendar, Clock, User } from "lucide-react";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { useToast } from "@/components/ui/use-toast";

const initialTeachers = [
  {
    id: "TC001",
    name: "Dr. Robert Anderson",
    email: "robert.a@school.edu",
    phone: "+1 234-567-9000",
    subject: "Mathematics",
    classes: 5,
    students: 125,
    status: "Active",
    joinDate: "2020-01-15",
    avatar: "",
  },
  {
    id: "TC002",
    name: "Prof. Maria Garcia",
    email: "maria.g@school.edu",
    phone: "+1 234-567-9001",
    subject: "Science",
    classes: 4,
    students: 98,
    status: "Active",
    joinDate: "2019-08-20",
    avatar: "",
  },
  {
    id: "TC003",
    name: "Ms. Jennifer Lee",
    email: "jennifer.l@school.edu",
    phone: "+1 234-567-9002",
    subject: "English",
    classes: 6,
    students: 150,
    status: "Active",
    joinDate: "2021-03-10",
    avatar: "",
  },
  {
    id: "TC004",
    name: "Mr. James Taylor",
    email: "james.t@school.edu",
    phone: "+1 234-567-9003",
    subject: "History",
    classes: 4,
    students: 110,
    status: "Active",
    joinDate: "2018-09-05",
    avatar: "",
  },
  {
    id: "TC005",
    name: "Dr. Lisa Chen",
    email: "lisa.c@school.edu",
    phone: "+1 234-567-9004",
    subject: "Computer Science",
    classes: 5,
    students: 135,
    status: "Active",
    joinDate: "2022-01-10",
    avatar: "",
  },
  {
    id: "TC006",
    name: "Mr. Thomas White",
    email: "thomas.w@school.edu",
    phone: "+1 234-567-9005",
    subject: "Physical Education",
    classes: 8,
    students: 200,
    status: "On Leave",
    joinDate: "2017-06-15",
    avatar: "",
  },
];

// Mock data for classes and schedule
const getTeacherClasses = (teacherId: string) => {
  const classes: Record<string, any[]> = {
    TC001: [
      { code: "MATH-101", name: "Mathematics - Algebra", grade: "Grade 10", students: 30, schedule: "Mon, Wed, Fri - 9:00 AM" },
      { code: "MATH-201", name: "Advanced Mathematics", grade: "Grade 11", students: 25, schedule: "Tue, Thu - 10:30 AM" },
      { code: "MATH-301", name: "Calculus", grade: "Grade 12", students: 20, schedule: "Mon, Wed - 2:00 PM" },
    ],
  };
  return classes[teacherId] || [];
};

const getTeacherSchedule = (teacherId: string) => {
  const schedule: Record<string, any[]> = {
    TC001: [
      { day: "Monday", time: "9:00 AM - 10:30 AM", class: "MATH-101", room: "Room 201" },
      { day: "Monday", time: "2:00 PM - 3:30 PM", class: "MATH-301", room: "Room 205" },
      { day: "Tuesday", time: "10:30 AM - 12:00 PM", class: "MATH-201", room: "Room 203" },
      { day: "Wednesday", time: "9:00 AM - 10:30 AM", class: "MATH-101", room: "Room 201" },
      { day: "Wednesday", time: "2:00 PM - 3:30 PM", class: "MATH-301", room: "Room 205" },
      { day: "Thursday", time: "10:30 AM - 12:00 PM", class: "MATH-201", room: "Room 203" },
      { day: "Friday", time: "9:00 AM - 10:30 AM", class: "MATH-101", room: "Room 201" },
    ],
  };
  return schedule[teacherId] || [];
};

export default function AdminTeachers() {
  const navigate = useNavigate();
  const [teachers, setTeachers] = useState(initialTeachers);
  const [isAddDialogOpen, setIsAddDialogOpen] = useState(false);
  const [isEditDialogOpen, setIsEditDialogOpen] = useState(false);
  const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);
  const [isClassesDialogOpen, setIsClassesDialogOpen] = useState(false);
  const [isScheduleDialogOpen, setIsScheduleDialogOpen] = useState(false);
  const [selectedTeacher, setSelectedTeacher] = useState<any>(null);
  const [formData, setFormData] = useState({
    firstName: "",
    lastName: "",
    email: "",
    phone: "",
    subject: "",
    joinDate: new Date().toISOString().split('T')[0],
    status: "Active",
  });
  const { toast } = useToast();

  const totalTeachers = 478;
  const activeTeachers = 465;
  const newHires = 12;

  const handleAddSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const newTeacher = {
      id: `TC${String(teachers.length + 1).padStart(3, '0')}`,
      name: `${formData.firstName} ${formData.lastName}`,
      email: formData.email,
      phone: formData.phone,
      subject: formData.subject,
      classes: 0,
      students: 0,
      status: formData.status,
      joinDate: formData.joinDate,
      avatar: "",
    };
    setTeachers([...teachers, newTeacher]);
    toast({
      title: "Teacher Added",
      description: `${formData.firstName} ${formData.lastName} has been successfully added to the faculty.`,
    });
    setIsAddDialogOpen(false);
    resetForm();
  };

  const handleEditSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedTeacher) return;
    
    const updatedTeachers = teachers.map((t) =>
      t.id === selectedTeacher.id
        ? {
            ...t,
            name: `${formData.firstName} ${formData.lastName}`,
            email: formData.email,
            phone: formData.phone,
            subject: formData.subject,
            status: formData.status,
            joinDate: formData.joinDate,
          }
        : t
    );
    setTeachers(updatedTeachers);
    toast({
      title: "Teacher Updated",
      description: `${formData.firstName} ${formData.lastName}'s information has been updated.`,
    });
    setIsEditDialogOpen(false);
    setSelectedTeacher(null);
    resetForm();
  };

  const handleDelete = () => {
    if (!selectedTeacher) return;
    
    setTeachers(teachers.filter((t) => t.id !== selectedTeacher.id));
    toast({
      title: "Teacher Removed",
      description: `${selectedTeacher.name} has been removed from the faculty.`,
      variant: "destructive",
    });
    setIsDeleteDialogOpen(false);
    setSelectedTeacher(null);
  };

  const handleEdit = (teacher: any) => {
    const nameParts = teacher.name.split(' ');
    setSelectedTeacher(teacher);
    setFormData({
      firstName: nameParts[0] || "",
      lastName: nameParts.slice(1).join(' ') || "",
      email: teacher.email,
      phone: teacher.phone,
      subject: teacher.subject,
      joinDate: teacher.joinDate,
      status: teacher.status,
    });
    setIsEditDialogOpen(true);
  };

  const handleDeleteClick = (teacher: any) => {
    setSelectedTeacher(teacher);
    setIsDeleteDialogOpen(true);
  };

  const handleViewClasses = (teacher: any) => {
    setSelectedTeacher(teacher);
    setIsClassesDialogOpen(true);
  };

  const handleViewSchedule = (teacher: any) => {
    setSelectedTeacher(teacher);
    setIsScheduleDialogOpen(true);
  };

  const resetForm = () => {
    setFormData({
      firstName: "",
      lastName: "",
      email: "",
      phone: "",
      subject: "",
      joinDate: new Date().toISOString().split('T')[0],
      status: "Active",
    });
  };

  return (
    <DashboardLayout title="Teachers" userRole="admin">
      {/* Stats Row */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <StatCard
          icon={<GraduationCap className="w-6 h-6 text-purple-600" />}
          iconBgColor="bg-purple-100"
          label="Total Teachers"
          value={totalTeachers}
        />
        <StatCard
          icon={<Award className="w-6 h-6 text-blue-600" />}
          iconBgColor="bg-blue-100"
          label="Active Teachers"
          value={activeTeachers}
        />
        <StatCard
          icon={<UserPlus className="w-6 h-6 text-green-600" />}
          iconBgColor="bg-green-100"
          label="New Hires This Year"
          value={newHires}
        />
      </div>

      {/* Main Content */}
      <Card>
        <CardHeader>
          <div className="flex items-center justify-between">
            <div>
              <CardTitle>Teacher Management</CardTitle>
              <p className="text-sm text-muted-foreground mt-1">
                Manage faculty members and their assignments
              </p>
            </div>
            <div className="flex items-center gap-3">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                <Input
                  placeholder="Search teachers..."
                  className="pl-10 w-64"
                />
              </div>
              <Button onClick={() => setIsAddDialogOpen(true)}>
                <UserPlus className="w-4 h-4 mr-2" />
                Add Teacher
              </Button>
            </div>
          </div>
        </CardHeader>
        <CardContent>
          <div className="rounded-md border">
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Teacher</TableHead>
                  <TableHead>ID</TableHead>
                  <TableHead>Subject</TableHead>
                  <TableHead>Classes</TableHead>
                  <TableHead>Students</TableHead>
                  <TableHead>Contact</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead className="text-right">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {teachers.map((teacher) => (
                  <TableRow key={teacher.id}>
                    <TableCell>
                      <div className="flex items-center gap-3">
                        <Avatar className="w-10 h-10">
                          <AvatarImage src={teacher.avatar} alt={teacher.name} />
                          <AvatarFallback className="bg-primary text-primary-foreground">
                            {teacher.name.split(' ').map(n => n[0]).join('')}
                          </AvatarFallback>
                        </Avatar>
                        <div>
                          <div className="font-medium">{teacher.name}</div>
                          <div className="text-sm text-muted-foreground">{teacher.email}</div>
                        </div>
                      </div>
                    </TableCell>
                    <TableCell className="font-mono text-sm">{teacher.id}</TableCell>
                    <TableCell>
                      <div className="flex items-center gap-2">
                        <BookOpen className="w-4 h-4 text-muted-foreground" />
                        <span>{teacher.subject}</span>
                      </div>
                    </TableCell>
                    <TableCell>
                      <Badge variant="outline">{teacher.classes} Classes</Badge>
                    </TableCell>
                    <TableCell>
                      <Badge variant="secondary">{teacher.students} Students</Badge>
                    </TableCell>
                    <TableCell>
                      <div className="space-y-1">
                        <div className="flex items-center gap-2 text-sm">
                          <Mail className="w-3 h-3 text-muted-foreground" />
                          <span className="text-muted-foreground">{teacher.email}</span>
                        </div>
                        <div className="flex items-center gap-2 text-sm">
                          <Phone className="w-3 h-3 text-muted-foreground" />
                          <span className="text-muted-foreground">{teacher.phone}</span>
                        </div>
                      </div>
                    </TableCell>
                    <TableCell>
                      <Badge
                        variant={teacher.status === "Active" ? "default" : "secondary"}
                        className={teacher.status === "Active" ? "bg-green-500" : ""}
                      >
                        {teacher.status}
                      </Badge>
                    </TableCell>
                    <TableCell className="text-right">
                      <DropdownMenu>
                        <DropdownMenuTrigger asChild>
                          <Button variant="ghost" size="icon">
                            <MoreVertical className="w-4 h-4" />
                          </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                          <DropdownMenuItem onClick={() => navigate(`/admin/teachers/${teacher.id}`)}>
                            <User className="w-4 h-4 mr-2" />
                            View Profile
                          </DropdownMenuItem>
                          <DropdownMenuItem onClick={() => handleEdit(teacher)}>
                            <Edit className="w-4 h-4 mr-2" />
                            Edit Teacher
                          </DropdownMenuItem>
                          <DropdownMenuItem onClick={() => handleViewClasses(teacher)}>
                            <BookOpen className="w-4 h-4 mr-2" />
                            View Classes
                          </DropdownMenuItem>
                          <DropdownMenuItem onClick={() => handleViewSchedule(teacher)}>
                            <Calendar className="w-4 h-4 mr-2" />
                            View Schedule
                          </DropdownMenuItem>
                          <DropdownMenuItem
                            className="text-destructive"
                            onClick={() => handleDeleteClick(teacher)}
                          >
                            <Trash2 className="w-4 h-4 mr-2" />
                            Remove
                          </DropdownMenuItem>
                        </DropdownMenuContent>
                      </DropdownMenu>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </div>
        </CardContent>
      </Card>

      {/* Add Teacher Dialog */}
      <Dialog open={isAddDialogOpen} onOpenChange={setIsAddDialogOpen}>
        <DialogContent className="max-w-2xl">
          <DialogHeader>
            <DialogTitle>Add New Teacher</DialogTitle>
            <DialogDescription>
              Enter the teacher's information to add them to the faculty.
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={handleAddSubmit}>
            <div className="grid gap-4 py-4">
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="firstName">First Name *</Label>
                  <Input
                    id="firstName"
                    required
                    value={formData.firstName}
                    onChange={(e) => setFormData({ ...formData, firstName: e.target.value })}
                    placeholder="Robert"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="lastName">Last Name *</Label>
                  <Input
                    id="lastName"
                    required
                    value={formData.lastName}
                    onChange={(e) => setFormData({ ...formData, lastName: e.target.value })}
                    placeholder="Anderson"
                  />
                </div>
              </div>
              <div className="space-y-2">
                <Label htmlFor="email">Email *</Label>
                <Input
                  id="email"
                  type="email"
                  required
                  value={formData.email}
                  onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                  placeholder="robert.a@school.edu"
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="phone">Phone *</Label>
                <Input
                  id="phone"
                  type="tel"
                  required
                  value={formData.phone}
                  onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                  placeholder="+1 234-567-9000"
                />
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="subject">Subject *</Label>
                  <Select
                    value={formData.subject}
                    onValueChange={(value) => setFormData({ ...formData, subject: value })}
                    required
                  >
                    <SelectTrigger id="subject">
                      <SelectValue placeholder="Select subject" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Mathematics">Mathematics</SelectItem>
                      <SelectItem value="Science">Science</SelectItem>
                      <SelectItem value="English">English</SelectItem>
                      <SelectItem value="History">History</SelectItem>
                      <SelectItem value="Computer Science">Computer Science</SelectItem>
                      <SelectItem value="Physical Education">Physical Education</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div className="space-y-2">
                  <Label htmlFor="joinDate">Join Date *</Label>
                  <Input
                    id="joinDate"
                    type="date"
                    required
                    value={formData.joinDate}
                    onChange={(e) => setFormData({ ...formData, joinDate: e.target.value })}
                  />
                </div>
              </div>
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" onClick={() => setIsAddDialogOpen(false)}>
                Cancel
              </Button>
              <Button type="submit">
                <UserPlus className="w-4 h-4 mr-2" />
                Add Teacher
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Edit Teacher Dialog */}
      <Dialog open={isEditDialogOpen} onOpenChange={setIsEditDialogOpen}>
        <DialogContent className="max-w-2xl">
          <DialogHeader>
            <DialogTitle>Edit Teacher</DialogTitle>
            <DialogDescription>
              Update the teacher's information in the system.
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={handleEditSubmit}>
            <div className="grid gap-4 py-4">
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="edit-firstName">First Name *</Label>
                  <Input
                    id="edit-firstName"
                    required
                    value={formData.firstName}
                    onChange={(e) => setFormData({ ...formData, firstName: e.target.value })}
                    placeholder="Robert"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="edit-lastName">Last Name *</Label>
                  <Input
                    id="edit-lastName"
                    required
                    value={formData.lastName}
                    onChange={(e) => setFormData({ ...formData, lastName: e.target.value })}
                    placeholder="Anderson"
                  />
                </div>
              </div>
              <div className="space-y-2">
                <Label htmlFor="edit-email">Email *</Label>
                <Input
                  id="edit-email"
                  type="email"
                  required
                  value={formData.email}
                  onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                  placeholder="robert.a@school.edu"
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="edit-phone">Phone *</Label>
                <Input
                  id="edit-phone"
                  type="tel"
                  required
                  value={formData.phone}
                  onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                  placeholder="+1 234-567-9000"
                />
              </div>
              <div className="grid grid-cols-3 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="edit-subject">Subject *</Label>
                  <Select
                    value={formData.subject}
                    onValueChange={(value) => setFormData({ ...formData, subject: value })}
                    required
                  >
                    <SelectTrigger id="edit-subject">
                      <SelectValue placeholder="Select subject" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Mathematics">Mathematics</SelectItem>
                      <SelectItem value="Science">Science</SelectItem>
                      <SelectItem value="English">English</SelectItem>
                      <SelectItem value="History">History</SelectItem>
                      <SelectItem value="Computer Science">Computer Science</SelectItem>
                      <SelectItem value="Physical Education">Physical Education</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div className="space-y-2">
                  <Label htmlFor="edit-joinDate">Join Date *</Label>
                  <Input
                    id="edit-joinDate"
                    type="date"
                    required
                    value={formData.joinDate}
                    onChange={(e) => setFormData({ ...formData, joinDate: e.target.value })}
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="edit-status">Status *</Label>
                  <Select
                    value={formData.status}
                    onValueChange={(value) => setFormData({ ...formData, status: value })}
                    required
                  >
                    <SelectTrigger id="edit-status">
                      <SelectValue placeholder="Select status" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Active">Active</SelectItem>
                      <SelectItem value="On Leave">On Leave</SelectItem>
                      <SelectItem value="Inactive">Inactive</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" onClick={() => setIsEditDialogOpen(false)}>
                Cancel
              </Button>
              <Button type="submit">
                <Edit className="w-4 h-4 mr-2" />
                Update Teacher
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Delete Confirmation Dialog */}
      <AlertDialog open={isDeleteDialogOpen} onOpenChange={setIsDeleteDialogOpen}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Are you sure?</AlertDialogTitle>
            <AlertDialogDescription>
              This action cannot be undone. This will permanently remove{" "}
              <span className="font-semibold">{selectedTeacher?.name}</span> from the faculty
              and remove all associated data.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Cancel</AlertDialogCancel>
            <AlertDialogAction
              onClick={handleDelete}
              className="bg-destructive text-destructive-foreground hover:bg-destructive/90"
            >
              <Trash2 className="w-4 h-4 mr-2" />
              Remove
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>

      {/* View Classes Dialog */}
      <Dialog open={isClassesDialogOpen} onOpenChange={setIsClassesDialogOpen}>
        <DialogContent className="max-w-3xl">
          <DialogHeader>
            <DialogTitle className="flex items-center gap-2">
              <BookOpen className="w-5 h-5" />
              Classes - {selectedTeacher?.name}
            </DialogTitle>
            <DialogDescription>
              View all classes taught by this teacher.
            </DialogDescription>
          </DialogHeader>
          <div className="py-4">
            {selectedTeacher && (
              <div className="rounded-md border">
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>Course Code</TableHead>
                      <TableHead>Course Name</TableHead>
                      <TableHead>Grade</TableHead>
                      <TableHead>Students</TableHead>
                      <TableHead>Schedule</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {getTeacherClasses(selectedTeacher.id).map((classItem, index) => (
                      <TableRow key={index}>
                        <TableCell className="font-mono">
                          <Badge variant="outline">{classItem.code}</Badge>
                        </TableCell>
                        <TableCell className="font-medium">{classItem.name}</TableCell>
                        <TableCell>{classItem.grade}</TableCell>
                        <TableCell>
                          <Badge variant="secondary">{classItem.students} Students</Badge>
                        </TableCell>
                        <TableCell className="text-sm text-muted-foreground">
                          <div className="flex items-center gap-2">
                            <Clock className="w-4 h-4" />
                            {classItem.schedule}
                          </div>
                        </TableCell>
                      </TableRow>
                    ))}
                    {getTeacherClasses(selectedTeacher.id).length === 0 && (
                      <TableRow>
                        <TableCell colSpan={5} className="text-center text-muted-foreground py-8">
                          No classes assigned to this teacher.
                        </TableCell>
                      </TableRow>
                    )}
                  </TableBody>
                </Table>
              </div>
            )}
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setIsClassesDialogOpen(false)}>
              Close
            </Button>
            <Button onClick={() => {
              setIsClassesDialogOpen(false);
              if (selectedTeacher) {
                navigate(`/admin/teachers/${selectedTeacher.id}`);
              }
            }}>
              View Full Profile
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* View Schedule Dialog */}
      <Dialog open={isScheduleDialogOpen} onOpenChange={setIsScheduleDialogOpen}>
        <DialogContent className="max-w-3xl">
          <DialogHeader>
            <DialogTitle className="flex items-center gap-2">
              <Calendar className="w-5 h-5" />
              Schedule - {selectedTeacher?.name}
            </DialogTitle>
            <DialogDescription>
              View the weekly schedule for this teacher.
            </DialogDescription>
          </DialogHeader>
          <div className="py-4">
            {selectedTeacher && (
              <div className="rounded-md border">
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>Day</TableHead>
                      <TableHead>Time</TableHead>
                      <TableHead>Class</TableHead>
                      <TableHead>Room</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {getTeacherSchedule(selectedTeacher.id).map((schedule, index) => (
                      <TableRow key={index}>
                        <TableCell className="font-medium">{schedule.day}</TableCell>
                        <TableCell>
                          <div className="flex items-center gap-2">
                            <Clock className="w-4 h-4 text-muted-foreground" />
                            {schedule.time}
                          </div>
                        </TableCell>
                        <TableCell>
                          <Badge variant="outline" className="font-mono">
                            {schedule.class}
                          </Badge>
                        </TableCell>
                        <TableCell>{schedule.room}</TableCell>
                      </TableRow>
                    ))}
                    {getTeacherSchedule(selectedTeacher.id).length === 0 && (
                      <TableRow>
                        <TableCell colSpan={4} className="text-center text-muted-foreground py-8">
                          No schedule available for this teacher.
                        </TableCell>
                      </TableRow>
                    )}
                  </TableBody>
                </Table>
              </div>
            )}
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setIsScheduleDialogOpen(false)}>
              Close
            </Button>
            <Button onClick={() => {
              setIsScheduleDialogOpen(false);
              if (selectedTeacher) {
                navigate(`/admin/teachers/${selectedTeacher.id}`);
              }
            }}>
              View Full Profile
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </DashboardLayout>
  );
}

